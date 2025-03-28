<?php


namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin')->except(['edit', 'update']);
    }

    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
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

        return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        // Vérifier si l'utilisateur actuel est l'administrateur ou s'il modifie son propre profil
        if (Auth::user()->role != 'admin' && Auth::user()->idUtilisateur != $user->idUtilisateur) {
            return redirect()->route('home')->with('error', 'Vous n\'êtes pas autorisé à modifier ce profil.');
        }
        
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Vérifier si l'utilisateur actuel est l'administrateur ou s'il modifie son propre profil
        if (Auth::user()->role != 'admin' && Auth::user()->idUtilisateur != $user->idUtilisateur) {
            return redirect()->route('home')->with('error', 'Vous n\'êtes pas autorisé à modifier ce profil.');
        }
        
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id.',idUtilisateur',
            'fonction' => 'nullable|string|max:255',
            'societe' => 'nullable|string|max:255',
            'direction' => 'nullable|string|max:255',
            'service' => 'nullable|string|max:255',
        ]);
        
        $userData = [
            'nom' => $request->nom,
            'email' => $request->email,
            'fonction' => $request->fonction,
            'societe' => $request->societe,
            'direction' => $request->direction,
            'service' => $request->service,
        ];
        
        // Seul l'administrateur peut changer le rôle
        if (Auth::user()->role == 'admin' && $request->has('role')) {
            $request->validate([
                'role' => 'required|in:utilisateur,responsable,archiviste,admin'
            ]);
            $userData['role'] = $request->role;
        }
        
        // Mise à jour du mot de passe si fourni
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed'
            ]);
            $userData['password'] = Hash::make($request->password);
        }
        
        $user->update($userData);
        
        if (Auth::user()->role == 'admin') {
            return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès.');
        } else {
            return redirect()->route('home')->with('success', 'Profil mis à jour avec succès.');
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Vérifier si l'utilisateur a des demandes associées
        if ($user->demandes()->count() > 0 || $user->demandesResponsable()->count() > 0 || $user->demandesArchiviste()->count() > 0) {
            return back()->withErrors(['message' => 'Cet utilisateur est associé à des demandes et ne peut pas être supprimé.']);
        }
        
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }
}
