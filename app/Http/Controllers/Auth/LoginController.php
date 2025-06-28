<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Handle a login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Siapkan credentials untuk authentication
        $credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];

        // Coba login
        if (Auth::attempt($credentials)) {
            // Authentication berhasil
            $request->session()->regenerate();
            
            // Debug: Cek nilai is_admin (sesuai dengan nama kolom di database)
            $user = Auth::user();
            \Log::info('User Login Debug:', [
                'id' => $user->id_nama,
                'username' => $user->username,
                'is_admin' => $user->is_admin,
                'is_admin_type' => gettype($user->is_admin)
            ]);
            
            // Cek apakah user adalah admin - gunakan is_admin (sesuai database)
            if ($user->is_admin === true || $user->is_admin === 1 || $user->is_admin == '1') {
                \Log::info('Redirecting to admin dashboard');
                return redirect('/admin/dashboard');
            }
            
            // Jika bukan admin, redirect ke halaman utama
            \Log::info('Redirecting to home');
            return redirect('/')->with('success', 'Login berhasil!');
        }

        // Jika login gagal
        return back()->withErrors([
            'username' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->withInput();
    }

    /**
     * Log the user out.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah logout.');
    }
}