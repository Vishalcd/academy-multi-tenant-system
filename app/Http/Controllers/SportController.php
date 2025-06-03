<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Sport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class SportController extends Controller
{
    // @Method GET
    // @Route /sports
    public function index(Request $request)
    {
        $search = $request->get('search');
        $academyId = activeAcademy()?->id;
        $academyFilter = $request->input('academy_id');
        $sort = $request->input('sport_fees'); // "low_to_high" or "high_to_low"

        $sports = Sport::with('academy')
            ->when(Auth::user()->role !== 'admin', function ($query) use ($academyId) {
                $query->where('academy_id', $academyId);
            })
            ->when(Auth::user()->role === 'admin' && $academyFilter, function ($q) use ($academyFilter) {
                $q->where('academy_id', $academyFilter);
            })
            ->when($search, function ($query) use ($search) {
                $query->where('sport_title', 'ILIKE', "%$search%");
            })
            ->when($sort === 'low_to_high', function ($query) {
                $query->orderBy('sport_fees', 'asc');
            })
            ->when($sort === 'high_to_low', function ($query) {
                $query->orderBy('sport_fees', 'desc');
            })
            ->paginate(20)
            ->appends(request()->query());

        $academies = getAllAcademies();

        return view('sports.index', compact('sports', 'academies', 'sort'));

    }

    // @Method POST
    // @Route /sports
    public function store(Request $request)
    {
        // validate data
        $validatedData = $request->validate([
            'sport_title' => 'required|string|max:255',
            'sport_fees' => 'required|string',
            'photo' => 'required|image|mimes:png,jpg,jpeg,gif,webp:max:10240',
        ]);

        $academyId = activeAcademy()?->id;
        $validatedData['academy_id'] = $academyId;

        // check for image
        if ($request->hasFile('photo')) {
            // store the file and get
            $path = $request->file("photo")->store('sports', 'public');

            // Add path to validated data
            $validatedData['photo'] = $path;
        }

        // Submit to database
        Sport::create($validatedData);

        // redirect to all sport page
        return redirect()->route('sports.index')->with('success', 'Sport Added successfully.');
    }

    // @Method GET
    // @Route /sports/{id}
    public function show(Sport $sport)
    {
        Gate::authorize('view', $sport);

        $coaches = Employee::with('user')
            ->where('job_title', 'coach')
            ->where('sport_id', $sport->id)
            ->whereHas('user', function ($query) use ($sport) {
                $query->where('academy_id', $sport->academy_id);
            })
            ->get();

        return view('sports.show')->with([
            'sport' => $sport,
            'coaches' => $coaches,
        ]);
    }

    // @Method POST
    // @Route /sports/{id}
    public function update(Request $request, Sport $sport)
    {
        // Authorize update action for the current user on this sport
        Gate::authorize('update', $sport);

        // validate data
        $validatedData = $request->validate([
            'sport_title' => 'required|string|max:255',
            'sport_fees' => 'required|string',
            'photo' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp:max:10240',
        ]);

        // check for image
        if ($request->hasFile('photo')) {
            // delete old photo
            Storage::delete('public/sports/'.basename($sport->photo));

            // store the file and get
            $path = $request->file("photo")->store('sports', 'public');

            // Add path to validated data
            $validatedData['photo'] = $path;
        }

        // Submit to database
        $sport->update($validatedData);

        // redirect to sport page
        return redirect()->route('sports.show', $sport->id)->with('success', 'Sport Updated successfully.');
    }

    // @Method DELETE
    // @Route /sports/{id}
    public function destroy(Sport $sport)
    {
        // Authorize delete action for the current user on this sport
        Gate::authorize('delete', $sport);

        // delete image before deleting resource
        Storage::delete('public/storage/'.$sport->photo);

        // Delete the resource
        $sport->delete();

        // redirect to all sports page
        return redirect()->route('sports.index')->with('success', 'Sport Deleted successfully.');
    }
}
