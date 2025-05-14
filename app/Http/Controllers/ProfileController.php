<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProfileController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Display the profile page.
     */
    public function profile()
    {
        return view('profile');
    }

    /**
     * Display the settings page.
     */
    public function settings()
    {
        return view('settings');
    }

    /**
     * Update the user's profile settings.
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $validatedData = $request->validate([
            'username' => [
                'required', 
                'string', 
                'max:255',
                Rule::unique('users', 'username')->ignore($user->id)
            ],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id)
            ],
            'phone_number' => 'nullable|string|max:20',
            'current_password' => 'required|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        // Update user data
        $user->username = $request->username;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        
        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}