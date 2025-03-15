<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = [
            'idUtilisateur' => $request->input('idUtilisateur'),
            'password' => $request->input('password'),
        ];

        $user = User::find($credentials['idUtilisateur']);

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            return redirect()->route('home'); // Redirect to dashboard
        }

        return back()->withErrors(['message' => 'Invalid login credentials']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'Fonction' => 'nullable|string|max:255',
            'Societe' => 'nullable|string|max:255',
            'Direction' => 'nullable|string|max:255',
            'Service' => 'nullable|string|max:255',
            'role' => 'required|string|in:Utilisateur,Responsable,Archiviste,Admin',
        ]);

        User::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'Fonction' => $request->Fonction,
            'Societe' => $request->Societe,
            'Direction' => $request->Direction,
            'Service' => $request->Service,
            'role' => $request->role,
        ]);

        return redirect('/login')->with('success', 'Registration successful! Please login.');
    }
}