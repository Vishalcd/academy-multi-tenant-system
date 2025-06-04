<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // @Method POST
    // @Route /users/{id}
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        // Manual validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|integer',
            'address' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp|max:10240',
        ]);

        // Redirect to custom route if validation fails
        if ($validator->fails()) {
            return redirect('/'.$user->role.'s/me/#update-me') // e.g. 'profile.edit'
                ->withErrors($validator)
                ->withInput();
        }

        $validatedData = $validator->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file("photo")->store('users', 'public');
            $validatedData['photo'] = $path;
        }

        $user->update($validatedData);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    // @Method POST
    // @Route /users/{id}/update-password
    public function updatePassword(Request $request, string $id)
    {
        // Manually handle validation
        $validator = Validator::make($request->all(), [
            'oldPassword' => 'required',
            'newPassword' => 'required|string|min:8|confirmed',
        ]);

        $user = User::findOrFail($id);

        // Custom redirect on validation error
        if ($validator->fails()) {
            return redirect('/'.$user->role.'s/me/#update-password') // or use a URL
                ->withErrors($validator)
                ->withInput();
        }

        $validatedData = $validator->validated();

        if (! Hash::check($validatedData['oldPassword'], $user->password)) {
            return redirect('/'.$user->role.'s/me/#update-password') // same or another route
                ->withErrors(['oldPassword' => 'Old password is incorrect.'])
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($validatedData['newPassword']),
        ]);

        return redirect()->back() // or somewhere else
            ->with('success', 'Password updated successfully.');
    }
}
