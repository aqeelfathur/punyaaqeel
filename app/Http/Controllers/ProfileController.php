<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function settings()
    {
        $user = Auth::user();
        return view('settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'string',
                'max:255',
                'min:3',
                Rule::unique('users')->ignore($user->id_nama, 'id_nama'),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id_nama, 'id_nama'),
            ],
            'phone_number' => 'nullable|string|max:20|regex:/^[\+]?[0-9\-\(\)\s]+$/',
            'current_password' => 'required|string',
            'password' => 'nullable|string|min:8|confirmed',
            'password_confirmation' => 'nullable|string|min:8',
        ], [
            'username.required' => 'Username is required.',
            'username.min' => 'Username must be at least 3 characters.',
            'username.unique' => 'This username is already taken.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'phone_number.regex' => 'Please enter a valid phone number.',
            'current_password.required' => 'Current password is required to save changes.',
            'password.min' => 'New password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors([
                'current_password' => 'The current password is incorrect.'
            ])->withInput();
        }

        // Prepare data for update
        $updateData = [
            'username' => $request->username,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ];

        // Update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        try {
            $user->update($updateData);
            
            return redirect()->back()->with('success', 'Settings updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Failed to update settings. Please try again.'
            ])->withInput();
        }
    }

    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'profile_image.required' => 'Please select an image file.',
            'profile_image.image' => 'The file must be an image.',
            'profile_image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, webp.',
            'profile_image.max' => 'The image may not be greater than 2MB.',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()->withErrors($validator);
        }

        try {
            $user = Auth::user();
            
            // Delete old image if exists
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // Store new image with unique filename
            $image = $request->file('profile_image');
            $filename = 'profile_' . $user->id_nama . '_' . time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('profile_images', $filename, 'public');
            
            // Update user profile
            $user->update([
                'profile_image' => $imagePath
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile image updated successfully!',
                    'image_url' => Storage::url($imagePath)
                ]);
            }

            return redirect()->back()->with('success', 'Profile image updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Profile image upload failed: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload image. Please try again.'
                ], 500);
            }
            
            return redirect()->back()->withErrors([
                'profile_image' => 'Failed to upload image. Please try again.'
            ]);
        }
    }

    public function deleteImage(Request $request)
    {
        try {
            $user = Auth::user();
            
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $user->update([
                'profile_image' => null
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile image deleted successfully!'
                ]);
            }

            return redirect()->back()->with('success', 'Profile image deleted successfully!');

        } catch (\Exception $e) {
            \Log::error('Profile image deletion failed: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete image. Please try again.'
                ], 500);
            }
            
            return redirect()->back()->withErrors([
                'error' => 'Failed to delete image. Please try again.'
            ]);
        }
    }

    public function getImageUrl()
    {
        $user = Auth::user();
        $imageUrl = $user->profile_image 
            ? Storage::url($user->profile_image) 
            : asset('images/default-avatar.png');
        
        return response()->json([
            'success' => true,
            'image_url' => $imageUrl,
            'has_image' => (bool) $user->profile_image
        ]);
    }
}