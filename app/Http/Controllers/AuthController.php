<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    function index() {
        return view('login');
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('username', 'password');

            if (Auth::attempt($credentials)) {
                return redirect('/')->with('success', 'Berhasil Login');
            }

            return redirect('/login')->with('error', 'Pasword or Username Salah');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Pasword or Username Salah');
        }
    }


    public function logout(Request $request)
    {
        try {
            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('/login')->with('success', 'Berhasil Logout');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal Logout');
        }
    }

    public function showRegistrationForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'registration_key' => 'required|string',
        ]);

        if ($request->registration_key !== 'Chemical123!') {
            return redirect()->back()->withErrors(['registration_key' => 'Invalid registration key.'])->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Registrasi berhasil!');
    }
}
