<?php

namespace App\Http\Controllers;

use App\Models\DemandeDocument;
use App\Models\Document;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $unreadNotifications = Notification::where('idDestinataire', $user->idUtilisateur)
            ->where('read', false)
            ->count();
        
        $stats = [
            'total_documents' => Document::count(),
            'total_demandes' => DemandeDocument::count(),
            'mes_demandes' => DemandeDocument::where('idUtilisateur', $user->idUtilisateur)->count(),
            'demandes_en_attente' => 0
        ];
        
        if ($user->role == 'admin') {
            // Apply filters for admin
            $demandesQuery = DemandeDocument::with(['utilisateur', 'document']);
            
            // Filter by user name if provided
            if ($request->filled('utilisateur')) {
                $userName = $request->utilisateur;
                $demandesQuery->whereHas('utilisateur', function($query) use ($userName) {
                    $query->where('nom', 'like', '%' . $userName . '%');
                });
            }
            
            // Filter by date range if provided
            if ($request->filled('date_debut')) {
                $demandesQuery->where('dateSoumission', '>=', $request->date_debut);
            }
            
            if ($request->filled('date_fin')) {
                $demandesQuery->where('dateSoumission', '<=', $request->date_fin);
            }
            
            // Filter by status if provided
            if ($request->filled('statut')) {
                $demandesQuery->where('statut', $request->statut);
            }
            
            // Get filtered demands
            $demandes = $demandesQuery->latest()->paginate(10);
            $stats['demandes_en_attente'] = DemandeDocument::where('statut', 'en_attente')->count();
            
            // Add status distribution stats for charts
            $stats['statusDistribution'] = [
                'en_attente' => DemandeDocument::where('statut', 'en_attente')->count(),
                'approuvé_responsable' => DemandeDocument::where('statut', 'approuvé_responsable')->count(),
                'approuvé_archiviste' => DemandeDocument::where('statut', 'approuvé_archiviste')->count(),
                'rejeté' => DemandeDocument::where('statut', 'rejeté')->count(),
                'terminé' => DemandeDocument::where('statut', 'terminé')->count()
            ];
            
        } elseif ($user->role == 'responsable') {
            $demandes = DemandeDocument::where('idResponsableService', $user->idUtilisateur)
            ->orWhere('idUtilisateur', $user->idUtilisateur)
            ->with(['utilisateur', 'document'])
            ->latest()
            ->paginate(10);
            $stats['demandes_en_attente'] = DemandeDocument::where('idResponsableService', $user->idUtilisateur)
                ->where('statut', 'en_attente')
                ->count();
        } elseif ($user->role == 'archiviste') {
            $demandes = DemandeDocument::where('statut', 'approuvé_responsable')
            ->orWhere('idArchiviste', $user->idUtilisateur)
            ->orWhere('idUtilisateur', $user->idUtilisateur)
            ->with(['utilisateur', 'document'])
            ->latest()
            ->paginate(10);
            $stats['demandes_en_attente'] = DemandeDocument::where('idArchiviste', $user->idUtilisateur)
                ->where('statut', 'approuvé_responsable')
                ->count();
        } else {
            $demandes = DemandeDocument::where('idUtilisateur', $user->idUtilisateur)
                ->with(['document'])
                ->latest()
                ->paginate(10);
        }

        return view('home', compact('stats', 'unreadNotifications', 'demandes'));
    }
}