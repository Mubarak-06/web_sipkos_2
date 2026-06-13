<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ===============================
    // TAMPILKAN LOGIN UTAMA
    // ===============================
    public function showLogin()
    {
        return view('login');
    }

    // Agar route lama /login/user tetap aman
    public function showUserLogin()
    {
        return $this->showLogin();
    }

    // Agar route lama /login/admin tidak menampilkan halaman admin
    public function showAdminLogin()
    {
        return redirect()->route('login');
    }

    // ===============================
    // LOGIN SATU PINTU
    // ===============================
    public function login(Request $request)
    {
        return $this->processLogin($request);
    }

    // Dipakai jika di web.php kamu masih memakai loginUnified
    public function loginUnified(Request $request)
    {
        return $this->processLogin($request);
    }

    // Agar route lama login user tetap aman
    public function loginUser(Request $request)
    {
        return $this->processLogin($request);
    }

    // Agar route lama login admin tetap aman
    public function loginAdmin(Request $request)
    {
        return $this->processLogin($request);
    }

    // ===============================
    // PROSES LOGIN UTAMA
    // ===============================
    private function processLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return back()
                ->withInput()
                ->with('error', 'Email atau password salah.');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin');
        }

        return redirect()->route('home');
    }

    // ===============================
    // REGISTER USER
    // ===============================
    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Registrasi berhasil. Silakan login.');
    }

    // ===============================
    // LOGOUT
    // ===============================
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }
}