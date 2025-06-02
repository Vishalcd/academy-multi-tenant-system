<?php

namespace App\Http\Controllers;

use App\Mail\SalarySettled;
use App\Mail\Welcome;
use App\Models\Employee;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {

        //get search input & filter
        $search = $request->input('search');
        $sportId = $request->input('sport_id');
        $salarySettled = $request->input('salary_settled');
        $academyFilter = $request->input('academy_id'); // Admin-specific academy filter


        $academyId = activeAcademy()?->id;


        // Get All Employee
        $employees = Employee::with(['user', 'sport'])
            ->when(Auth::user()->role === 'admin' && $academyFilter, function ($query) use ($academyFilter) {
                // Admin-selected academy
                $query->whereHas('user', function ($q) use ($academyFilter) {
                    $q->where('academy_id', $academyFilter);
                });
            })
            ->when(Auth::user()->role !== 'admin', function ($query) use ($academyId) {
                // Filter by academy only if NOT admin
                $query->whereHas('user', function ($q) use ($academyId) {
                    $q->where('academy_id', $academyId);
                });
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->whereRaw('LOWER(name) LIKE ?', ['%'.strtolower($search).'%'])
                        ->orWhereRaw('LOWER(email) LIKE ?', ['%'.strtolower($search).'%']);
                });
            })
            ->when($salarySettled, function ($query) use ($salarySettled) {
                $query->where('salary_settled', $salarySettled);
            })
            ->when($sportId, function ($query) use ($sportId) {
                $query->where('sport_id', $sportId);
            })
            ->paginate(20)
            ->appends(request()->query()); // Preserve filters in pagination links

        // All Sports listed according to academy
        $sports = getAllSports();
        $academies = getAllAcademies();

        // Render Employees page
        return view("employees.index", compact('employees', 'search', 'sports', 'academies'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'sport_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:100|unique:users,email',
            'phone' => 'required|integer',
            'salary' => 'required|integer',
            'address' => 'required|string|max:255',
            'photo' => 'required|image|mimes:png,jpg,jpeg,gif,webp:max:10240',
        ]);

        // check for image
        if ($request->hasFile('photo')) {
            // store the file and get
            $path = $request->file("photo")->store('employees', 'public');

            // Add path to validated data
            $validatedData['photo'] = $path;
        }

        $academyId = activeAcademy()?->id;

        // Set default password 
        $validatedData['password'] = Hash::make('password');

        // Create user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'photo' => $validatedData['photo'],
            'password' => $validatedData['password'],
            'address' => $validatedData['address'],
            'academy_id' => $academyId,
            'role' => 'employee',
        ]);

        // Create employee record linked to user
        $user->employee()->create([
            'user_id' => $user->id,
            'sport_id' => $validatedData['sport_id'],
            'salary' => $validatedData['salary'],
            'last_paid' => null,
        ]);

        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'academy_name' => activeAcademy()->name,
            'academy_email' => activeAcademy()->email,
            'academy_address' => activeAcademy()->address,
            'academy_logo' => activeAcademy()->photo,
        ];
        Mail::to($user->email)->send(new Welcome($data));

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Get Employee Detail
        $employee = Employee::with(['user.academy', 'sport'])->find($id);
        $transactions = Transaction::where('user_id', $employee->user->id)->get();

        // Authorize view action for the current user on this employee
        Gate::authorize('view', $employee);

        // All Sports listed according to academy
        $sports = getAllSports();

        return view('employees.show')->with(['employee' => $employee, 'transactions' => $transactions, 'sports' => $sports]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $employee = Employee::with('user')->findOrFail($id); // get employee + user

        // Authorize update action for the current user on this employee
        Gate::authorize('update', $employee);

        $validatedData = $request->validate([
            'sport_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:100|unique:users,email,'.$employee->user->id,
            'phone' => 'required|integer',
            'salary' => 'required|integer',
            'address' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp:max:10240',
        ]);

        $academyId = activeAcademy()?->id;

        // check for image
        if ($request->hasFile('photo')) {
            // store the file and get
            $path = $request->file("photo")->store('employees', 'public');

            // Add path to validated data
            // Update user with photo
            $validatedData['photo'] = $path;
            $employee->user->update([
                'photo' => $validatedData['photo'],
            ]);
        }

        // Update user
        $employee->user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'address' => $validatedData['address'],
            'academy_id' => $academyId,
        ]);


        // Update employee
        $employee->update([
            'salary' => $validatedData['salary'],
            'sport_id' => $validatedData['sport_id'],
        ]);

        return redirect()->route('employees.show', $employee->id)->with('success', 'Employee updated successfully.');
    }

    /**
     * Add Salary Transaction.
     */
    public function depositSalary(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'transaction_amount' => 'required|numeric|min:1',
            'transaction_method' => 'required|string',
            'expense_id' => 'nullable',
        ]);
        // 1. Get Employee
        $employee = Employee::findOrFail($id);

        // 2.  get user id accociated with student
        $userId = $employee->user_id;

        if ($validatedData['transaction_amount'] > $employee->salary || $validatedData['transaction_amount'] < $employee->salary) {
            return redirect("/employees/$employee->id#deposit-salary")->withErrors(['transaction_amount' => 'Salary is not matching  (₹'.$employee->salary.').'])
                ->withInput();
        }

        // set academy id
        $academyId = activeAcademy()?->id;
        $validatedData['academy_id'] = $academyId;

        // 3. Create transaction
        Transaction::create([
            'user_id' => $userId,
            'transaction_amount' => $validatedData['transaction_amount'],
            'transaction_method' => $validatedData['transaction_method'],
            'transaction_for' => 'employee_salary',
            'academy_id' => $validatedData['academy_id'],

            // when we submit fee we set transaction as deposit transaction
            'transaction_type' => 'withdrawal',

            // Used only to get school expense transctions not students and employees transctions
            'expense_id' => null,
        ]);

        //  4. Update Employee Salary Status
        $employee->update(['salary_settled' => true, 'last_paid' => now()]);

        // 5. Send Email to user
        $user = User::findOrFail($employee->user_id);
        $data = [
            'name' => $user->name,
            'amount' => $validatedData['transaction_amount'],
            'month' => date('M'),
            'last_paid' => $employee->last_paid,
            'academy_logo' => activeAcademy()->photo,
            'academy_name' => activeAcademy()->name,
            'academy_email' => activeAcademy()->email,
            'academy_address' => activeAcademy()->address,
        ];
        Mail::to($user->email)->send(new SalarySettled($data));


        // redirect to employee page
        return redirect()->route('employees.show', $employee->id)->with('success', 'Salary Settled successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        // Delete the resource
        $user = User::find($employee->user_id);

        // Authorize delete action for the current user on this employee
        Gate::authorize('delete', $employee);

        $user->delete();

        // redirect to all expenses page
        return redirect()->route('employees.index')->with('success', 'Employee Deleted successfully.');
    }


    /**
     * Show Login Employee Profile 
     */
    public function showMe()
    {
        // get user from session
        $user = Auth::user(); // logged-in user

        // Get employee & transactions record that matches user_id
        $employee = Employee::with('user')->where('user_id', $user->id)->first();
        $transactions = Transaction::where('user_id', $employee->user->id)->get();

        return view('employees.me')->with(['employee' => $employee, 'transactions' => $transactions]);
    }

}
