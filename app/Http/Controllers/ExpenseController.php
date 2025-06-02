<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get search and sort inputs
        $search = $request->get('search');
        $sort = $request->get('sort');
        $academyFilter = $request->input('academy_id'); // Admin-specific academy filter

        // Get academy ID from session (for managers)
        $academyId = activeAcademy()?->id;

        // Build base query
        $query = Expense::query();

        // Admin: filter by selected academy if provided
        $query->when(Auth::user()->role === 'admin' && $academyFilter, function ($q) use ($academyFilter) {
            $q->where('academy_id', $academyFilter);
        });

        // Manager: always filter by current academy
        $query->when(Auth::user()->role !== 'admin', function ($q) use ($academyId) {
            $q->where('academy_id', $academyId);
        });

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ILIKE', "%$search%")
                    ->orWhere('description', 'ILIKE', "%$search%");
            });
        }

        // Apply sorting
        if ($sort === 'low_to_high') {
            $query->orderBy('total_price', 'asc');
        } elseif ($sort === 'high_to_low') {
            $query->orderBy('total_price', 'desc');
        } else {
            $query->latest();
        }

        // Get paginated results
        $expenses = $query->paginate(20)->appends($request->query());

        // Load all academies for admin dropdown
        $academies = getAllAcademies();

        // Return view
        return view("expenses.index", compact('expenses', 'search', 'sort', 'academies', 'academyFilter'));

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'unit_price' => 'required|integer',
            'total_price' => 'required|integer',
            'quantity' => 'required|integer',
            'shop_details' => 'required|string',
            'payment_type' => 'required',
            'payment_settled' => 'required',
            'photo' => 'required|image|mimes:png,jpg,jpeg,gif,webp:max:10240',
        ]);


        // check for image
        if ($request->hasFile('photo')) {
            // store the file and get
            $path = $request->file("photo")->store('recipts', 'public');

            // Add path to validated data
            $validatedData['photo'] = $path;
        }

        // set academy id
        $academyId = activeAcademy()?->id;
        $validatedData['academy_id'] = $academyId;

        // Submit to database
        $expense = Expense::create($validatedData);

        // Store Transaction
        Transaction::create([
            'expense_id' => $expense->id,
            'academy_id' => $academyId,
            'transaction_amount' => $validatedData['total_price'],
            'transaction_method' => $validatedData['payment_type'],
            'transaction_type' => 'withdrawal',
            'transaction_for' => 'expense',

            // Used only to get students and employees transctions not school expense transctions
            'user_id' => null,
        ]);

        // redirect to all expense page
        return redirect()->route('expenses.index')->with('success', 'Expense Added successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        // Authorize view action for the current user on this expense
        Gate::authorize('view', $expense);

        // Render Expense page
        return view("expenses.show")->with('expense', $expense);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        // Authorize update action for the current user on this expense
        Gate::authorize('update', $expense);

        // validate data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'unit_price' => 'required|integer',
            'total_price' => 'required|integer',
            'quantity' => 'required|integer',
            'shop_details' => 'required|string',
            'payment_type' => 'required',
            'payment_settled' => 'required',
            'photo' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp:max:10240',
        ]);

        // check for image
        if ($request->hasFile('photo')) {
            // delete old photo
            Storage::delete('public/recipts/'.basename($expense->photo));

            // store the file and get
            $path = $request->file("photo")->store('recipts', 'public');

            // Add path to validated data
            $validatedData['photo'] = $path;
        }

        // Update Transaction also
        Transaction::where('expense_id', $expense->id)->update([
            'transaction_amount' => $validatedData['total_price'],
            'transaction_method' => $validatedData['payment_type'],
        ]);

        // Submit to database
        $expense->update($validatedData);

        // redirect to expense page
        return redirect()->route('expenses.show', $expense->id)->with('success', 'Expense Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        // Authorize delete action for the current user on this expense
        Gate::authorize('delete', $expense);

        // delete image before deleting resource
        Storage::delete('public/storage/'.$expense->photo);

        // Delete the resource
        $expense->delete();

        // redirect to all expenses page
        return redirect()->route('expenses.index')->with('success', 'Expense Deleted successfully.');
    }

    /**
     * Download recipt from storage.
     */
    public function downloadRecipt(string $id)
    {

        $expense = Expense::findOrFail($id);

        $filePath = storage_path('app/public/'.$expense->photo);

        if (! file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->download($filePath);
    }
}
