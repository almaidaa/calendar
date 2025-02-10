<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}

