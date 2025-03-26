<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    public function showIdForm()
    {
        return view('auth.id-check');
    }

    public function checkId(Request $request)
    {
        $request->validate([
            'idUtilisateur' => 'required'
        ]);

        $user = User::find($request->idUtilisateur);

        if (!$user) {
            return back()->withErrors(['message' => 'User ID not found']);
        }

        // Generate temporary password
        $tempPassword = Str::random(8);
        $user->password = Hash::make($tempPassword);
        $user->save();

        // Send password by email
        Mail::raw("Your temporary password is: " . $tempPassword, function($message) use ($user) {
            $message->to($user->email)
                   ->subject('Your Temporary Login Password');
        });

        return redirect()->route('login')->with('success', 'A temporary password has been sent to your email.');
    }
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
            'fonction' => 'nullable|string|max:255',
            'societe' => 'nullable|string|max:255',
            'direction' => 'nullable|string|max:255',
            'service' => 'nullable|string|max:255',
            'role' => 'required|string|in:Utilisateur,Responsable,Archiviste,Admin',
            'site' => 'required|string|max:255',
        ]);

        User::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'fonction' => $request->fonction,
            'societe' => $request->societe,
            'direction' => $request->direction,
            'service' => $request->service,
            'role' => $request->role,
            'site' => $request->site,
        ]);

        return redirect('/login')->with('success', 'Registration successful! Please login.');
    }
}