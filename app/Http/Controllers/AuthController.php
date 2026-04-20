<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function index(){
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [], [
            'password' => 'contraseña'
        ]);

        if (!Auth::validate($credentials)) {
            return back()->with(['error' => 'Usuario o contraseña incorrecto.']);
        }

        $user = User::where('email', $credentials['email'])->first();

        if($user->estado === 0){
            return back()->with('warning', 'Tu cuenta ha sido desactivada.');
        }

        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard.home'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Has cerrado sesión correctamente.');
    }
}
