<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('register');
    }

    /**
     * Handle a registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:30|unique:users,username',
            'password' => 'required|string|min:8',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phonenumber' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Buat user baru
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password), // Gunakan kolom password standar
            'email' => $request->email,
            'phone_number' => $request->phonenumber, // Sesuaikan dengan nama kolom di migrasi
            'is_admin' => false,
        ]);

        // Login user
        auth()->login($user);

        // Redirect ke dashboard atau halaman utama
        return redirect('/')->with('success', 'Registrasi berhasil!');
    }
}