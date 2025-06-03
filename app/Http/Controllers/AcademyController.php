<?php

namespace App\Http\Controllers;

use App\Models\Academy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AcademyController extends Controller
{
    // @Method GET
    // @Route /academies
    public function index(Request $request)
    {
        $search = $request->input('search');

        $academies = Academy::when($search, function ($query) use ($search) {
            $query->where('name', 'ILIKE', "%$search%"); // 'ILIKE' for case-insensitive in PostgreSQL
        })->get();

        return view('academies.index')->with(['academies' => $academies, 'search' => $search]);
    }

    // @Method POST
    // @Route /academies
    public function store(Request $request)
    {
        // validate data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:100',
            'address' => 'required|string',
            'photo' => 'required|image|mimes:png,jpg,jpeg,gif,webp:max:10240',
            'favicon' => 'required|image|mimes:png,jpg,jpeg,gif,webp,ico:max:1024',
        ]);

        // check for image
        if ($request->hasFile('photo') && $request->hasFile('favicon')) {
            // store the file and get
            $photoPath = $request->file("photo")->store('logos', 'public');
            $faviconPath = $request->file("favicon")->store('favicons', 'public');

            // Add path to validated data
            $validatedData['photo'] = $photoPath;
            $validatedData['favicon'] = $faviconPath;
        }

        // Submit to database
        Academy::create($validatedData);

        // redirect to all sport page
        return redirect()->route('academies.index')->with('success', 'Academy Added successfully.');
    }

    // @Method GET
    // @Route /academies/{id}
    public function show(Academy $academy)
    {
        // Get all users with role 'manager' for this academy
        $managers = User::where('academy_id', $academy->id)
            ->where('role', 'manager')
            ->get();

        return view('academies.show')->with(['academy' => $academy, 'managers' => $managers]);
    }

    // @Method PUT
    // @Route /academies/{id}
    public function update(Request $request, Academy $academy)
    {
        // validate data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:100',
            'address' => 'required|string',
            'photo' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp:max:10240',
            'favicon' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp,ico:max:1024',
        ]);

        // check for image
        if ($request->hasFile('photo')) {
            // store the file and get
            $photoPath = $request->file("photo")->store('logos', 'public');
            // Add path to validated data
            $validatedData['photo'] = $photoPath;
        }

        if ($request->hasFile('favicon')) {
            // store the file and get
            $faviconPath = $request->file("favicon")->store('logos', 'public');
            // Add path to validated data
            $validatedData['favicon'] = $faviconPath;
        }

        // Submit to database
        $academy->update($validatedData);

        // redirect to all sport page
        return redirect()->route('academies.show', $academy->id)->with('success', 'Academy Updated successfully.');
    }

    // @Method DELETE
    // @Route /academies/{id}
    public function destroy(Academy $academy)
    {
        // delete image before deleting resource
        Storage::delete('public/storage/'.$academy->photo);
        Storage::delete('public/storage/'.$academy->favicon);


        // Delete the resource
        $academy->delete();

        // redirect to all sports page
        return redirect()->route('academies.index')->with('success', 'Academy Deleted successfully.');
    }


    // @Method DELETE
    // @Route /academies/{id}/managers
    public function storeManager(Request $request, int $id)
    {
        // validate data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:100|unique:users,email',
            'phone' => 'required|integer',
            'address' => 'required|string|max:255',
        ]);

        // Add Academy ID
        $validatedData['academy_id'] = $id;
        $validatedData['role'] = 'manager';
        $validatedData['password'] = Hash::make('password');

        // Submit to database
        User::create($validatedData);

        // redirect to all sport page
        return redirect()->route('academies.show', $id)->with('success', 'Manager Added successfully.');
    }
}
