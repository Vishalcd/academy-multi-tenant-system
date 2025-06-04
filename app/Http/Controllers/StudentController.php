<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Transaction;
use App\Models\User;

use App\Mail\FeeSubmitted;
use App\Mail\Welcome;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class StudentController extends Controller
{
    // @Method GET
    // @Route /students
    public function index(Request $request): View
    {
        // Get filters from request
        $search = $request->input('search');
        $feeFilter = $request->input('fees_settle');  // Expected: 'complete', 'due', or null
        $sportFilter = $request->input('sport_id');   // Optional class filter
        $academyFilter = $request->input('academy_id'); // Admin-specific academy filter

        $academyId = activeAcademy()?->id;

        // Build the query
        $students = Student::with(['user', 'sport'])
            ->when(Auth::user()->role === 'admin' && $academyFilter, function ($query) use ($academyFilter) {
                // Admin-selected academy
                $query->whereHas('user', function ($q) use ($academyFilter) {
                    $q->where('academy_id', $academyFilter);
                });
            })
            ->when(Auth::user()->role !== 'admin', function ($query) use ($academyId) {
                // Manager: filter only by their academy
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
            ->when($feeFilter, function ($query) use ($feeFilter) {
                $query->where('fees_settle', $feeFilter);
            })
            ->when($sportFilter, function ($query) use ($sportFilter) {
                $query->where('sport_id', $sportFilter);
            })->orderBy('created_at', 'asc') // 👈 Shows newly added students first
            ->paginate(20)
            ->appends(request()->query()); // Preserve filters in pagination links

        // All Sports listed according to academy
        $sports = getAllSports();
        $academies = getAllAcademies();

        return view("students.index", compact('students', 'search', 'sports', 'academies', 'academyFilter'));

    }

    // @Method POST
    // @Route /students
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:100|unique:users,email',
            'phone' => 'required|integer',
            'address' => 'required|string|max:255',
            'total_fees' => 'required|integer',
            'created_at' => 'required',
            'sport_id' => 'required|integer',
            'batch' => 'required|string',
            'photo' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp:max:10240',
        ]);

        dd($validatedData);

        // check for image
        if ($request->hasFile('photo')) {
            // store the file and get
            $path = $request->file("photo")->store('users', 'public');

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
            'address' => $validatedData['address'],
            'password' => $validatedData['password'],
            'photo' => $validatedData['photo'],
            'academy_id' => $academyId
        ]);

        // Create student record linked to user
        $user->student()->create([
            'user_id' => $user->id,
            'sport_id' => $validatedData['sport_id'],
            'total_fees' => $validatedData['total_fees'],
            'created_at' => $validatedData['created_at'],
            'fees_due' => $validatedData['total_fees'],
            'batch' => $validatedData['batch']
        ]);

        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'academy_logo' => activeAcademy()->photo,
            'academy_name' => activeAcademy()->name,
            'academy_email' => activeAcademy()->email,
            'academy_address' => activeAcademy()->address,
        ];
        Mail::to($user->email)->send(new Welcome($data));

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    // @Method GET
    // @Route /students/{id}
    public function show(string $id)
    {
        // Get Student Detail & Transctions
        $student = Student::with(['user.academy', 'sport'])->findOrFail($id);

        // Authorize view action for the current user on this student
        Gate::authorize('view', $student);

        $transactions = Transaction::where('user_id', $student->user->id)->get();
        // All Sports listed according to academy
        $sports = getAllSports();

        return view('students.show')->with(['student' => $student, 'transactions' => $transactions, 'sports' => $sports]);
    }

    // @Method GET
    // @Route /students/attendance
    public function studentAttendance(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'student') {
            abort(403, 'Unauthorized');
        }

        $student_id = Student::where('user_id', $user->id)->value('id');

        // Get month and year from query, fallback to current month/year
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        // Get attendance for current student filtered by month/year
        $attendances = Attendance::with(['student'])
            ->where('student_id', $student_id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date')
            ->get();

        return view('attendances.me', compact('attendances', 'month', 'year'));
    }

    // @Method PUT
    // @Route /students/{id}
    public function update(Request $request, $id)
    {
        $student = Student::with('user')->findOrFail($id); // get student + user

        // Authorize update action for the current user on this student
        Gate::authorize('update', $student);

        $validatedData = $request->validate([
            'sport_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:100|unique:users,email,'.$student->user->id,
            'phone' => 'required|integer',
            'total_fees' => 'required|integer',
            'address' => 'required|string|max:255',
            'created_at' => 'required|date',
            'batch' => 'required|date',
            'photo' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp:max:10240',
        ]);



        // check for image
        if ($request->hasFile('photo')) {
            // store the file and get
            $path = $request->file("photo")->store('users', 'public');

            // Add path to validated data
            // Update user with photo
            $validatedData['photo'] = $path;
            $student->user->update([
                'photo' => $validatedData['photo'],
            ]);
        }

        $academyId = activeAcademy()?->id;

        // Update user
        $student->user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'address' => $validatedData['address'],
            'academy_id' => $academyId,
        ]);

        // Update student
        $student->update([
            'sport_id' => $validatedData['sport_id'],
            'total_fees' => $validatedData['total_fees'],
            'created_at' => $validatedData['created_at'],
            'batch' => $validatedData['batch']

        ]);

        return redirect()->route('students.show', $student->id)->with('success', 'Student updated successfully.');
    }

    // @Method POST
    // @Route /students/{id}/deposit-fees
    public function depositFee(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'transaction_amount' => 'required|numeric|min:1',
            'transaction_method' => 'required|string',
            'expense_id' => 'nullable',
        ]);
        // 1. Get student
        $student = Student::with('sport')->findOrFail($id);

        // 2.  get user id accociated with student
        $userId = $student->user_id;

        if ($student->fees_due <= 0) {
            return redirect("/students/$student->id#deposit-fee")->withErrors(['transaction_amount' => 'No remaining fee to be paid.'])
                ->withInput();
        }

        if ($validatedData['transaction_amount'] > $student->fees_due) {
            return redirect("/students/$student->id#deposit-fee")->withErrors(['transaction_amount' => 'Amount exceeds remaining fee (₹'.$student->fees_due.').'])
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
            // Used only to get school expense transctions not students and employees transctions
            'academy_id' => $validatedData['academy_id'],

            // when we submit fee we set transaction as deposit transaction
            'transaction_type' => 'deposit',

            // Used only to get school expense transctions not students and employees transctions
            'expense_id' => null,
        ]);


        // 3. Update remaining fee
        $student->fees_due -= $validatedData['transaction_amount'];
        if ($student->fees_due <= 0) {
            $student->fees_due = 0; // ensure it doesn't go negative
            $student->fees_settle = true;   // or true/1/etc.
        }
        $student->save();

        // Send Email to user
        $user = User::findOrFail($student->user_id);
        $data = [
            'name' => $user->name,
            'amount' => $validatedData['transaction_amount'],
            'class' => "$student->class - Section $student->section",
            'fees_due' => $student->fees_due,
            'sport' => $student->sport->sport_title,
            'academy_logo' => activeAcademy()->photo,
            'academy_name' => activeAcademy()->name,
            'academy_email' => activeAcademy()->email,
            'academy_address' => activeAcademy()->address,
        ];
        Mail::to($user->email)->send(new FeeSubmitted($data));

        // redirect to studnet page
        return redirect()->route('students.show', $student->id)->with('success', 'Fees Deposit successfully!');
    }

    // @Method DELETE
    // @Route /students/{id}
    public function destroy(Student $student)
    {
        // Delete the resource
        $user = User::find($student->user_id);

        // Authorize delete action for the current user on this student
        Gate::authorize('delete', $student);

        $user->delete();

        // redirect to all expenses page
        return redirect()->route('students.index')->with('success', 'Student Deleted successfully.');
    }

    // @Method GET
    // @Route /students/me
    public function showMe()
    {
        // get user from session
        $user = Auth::user(); // logged-in user

        // Get student & transactions record that matches user_id
        $student = Student::with('user')->where('user_id', $user->id)->first();
        $transactions = Transaction::where('user_id', $student->user->id)->get();


        return view('students.me')->with(['student' => $student, 'transactions' => $transactions]);
    }



}
