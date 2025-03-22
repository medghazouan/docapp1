<?php

namespace App\Http\Controllers;

use App\Models\DemandeDocument;
use App\Models\Document;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $unreadNotifications = $this->getUnreadNotifications($user);
        
        if ($user->role == 'admin') {
            return $this->handleAdminDashboard($request, $unreadNotifications);
        }
        
        return $this->handleUserDashboard($user, $unreadNotifications);
    }

    private function getUnreadNotifications($user)
    {
        return Notification::where('idDestinataire', $user->idUtilisateur)
            ->where('read', false)
            ->count();
    }

    private function handleAdminDashboard($request, $unreadNotifications)
    {
        $documentTypes = Document::distinct()->pluck('type');
        $services = Document::distinct()->pluck('service');
        
        // Build base query with filters
        $statsQuery = $this->buildFilteredQuery($request);
        
        // Get paginated demands
        $demandes = $statsQuery->clone()
            ->with(['utilisateur', 'document'])
            ->latest()
            ->paginate(10);

        // Calculate statistics
        $stats = $this->calculateAdminStats($statsQuery);
        
        // Calculate status distribution
        $stats['statusDistribution'] = $this->calculateStatusDistribution($statsQuery);
        
        // Calculate average processing time
        $stats['avg_processing_time'] = $this->calculateAverageProcessingTime($statsQuery);

        return view('home', compact('stats', 'unreadNotifications', 'demandes', 'documentTypes', 'services'));
    }

    private function buildFilteredQuery(Request $request)
    {
        $query = DemandeDocument::query();

        if ($request->filled('utilisateur')) {
            $userName = $request->utilisateur;
            $query->whereHas('utilisateur', function($q) use ($userName) {
                $q->where('nom', 'like', '%' . $userName . '%');
            });
        }

        if ($request->filled('date_debut')) {
            $query->where('dateSoumission', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->where('dateSoumission', '<=', $request->date_fin);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('type')) {
            $documentType = $request->type;
            $query->whereHas('document', function($q) use ($documentType) {
                $q->where('type', $documentType);
            });
        }

        if ($request->filled('service')) {
            $service = $request->service;
            $query->whereHas('document', function($q) use ($service) {
                $q->where('service', $service);
            });
        }

        return $query;
    }

    private function calculateAdminStats($query)
    {
        $user = Auth::user();
        return [
            'total_documents' => Document::count(),
            'total_demandes' => $query->count(),
            'mes_demandes' => DemandeDocument::where('idUtilisateur', $user->idUtilisateur)->count(),
            'demandes_en_attente' => $query->clone()->where('statut', 'en_attente')->count()
        ];
    }

    private function calculateStatusDistribution($query)
    {
        $statuses = ['en_attente', 'approuvé_responsable', 'refusé_responsable', 
                     'approuvé_archiviste', 'refusé_archiviste', 'récupéré'];
        $distribution = [];

        foreach ($statuses as $status) {
            $distribution[$status] = $query->clone()->where('statut', $status)->count();
        }

        return $distribution;
    }

    private function calculateAverageProcessingTime($query)
    {
        $result = $query->clone()
            ->whereNotNull('dateRecuperation')
            ->whereNotNull('dateSoumission')
            ->selectRaw('ROUND(AVG(TIMESTAMPDIFF(HOUR, dateSoumission, dateRecuperation)), 2) as avg_time')
            ->first();

        return $result && $result->avg_time ? $result->avg_time : 0;
    }

    private function handleUserDashboard($user, $unreadNotifications)
    {
        $query = $this->buildUserQuery($user);
        
        $demandes = $query->clone()
            ->with(['document', 'utilisateur'])
            ->latest()
            ->paginate(10);

        $stats = $this->calculateUserStats($user, $query);

        return view('home', compact('stats', 'unreadNotifications', 'demandes'));
    }

    private function buildUserQuery($user)
    {
        $query = DemandeDocument::query();

        switch ($user->role) {
            case 'responsable':
                $query->where(function($q) use ($user) {
                    $q->where('idResponsableService', $user->idUtilisateur)
                      ->orWhere('idUtilisateur', $user->idUtilisateur);
                });
                break;
            case 'archiviste':
                $query->where(function($q) use ($user) {
                    $q->where('statut', 'approuvé_responsable')
                      ->orWhere('idArchiviste', $user->idUtilisateur)
                      ->orWhere('idUtilisateur', $user->idUtilisateur);
                });
                break;
            default:
                $query->where('idUtilisateur', $user->idUtilisateur);
        }

        return $query;
    }

    private function calculateUserStats($user, $query)
    {
        return [
            'total_documents' => Document::count(),
            'total_demandes' => $query->count(),
            'mes_demandes' => DemandeDocument::where('idUtilisateur', $user->idUtilisateur)->count(),
            'demandes_en_attente' => $this->getAwaitingDemandsCount($user, $query)
        ];
    }

    private function getAwaitingDemandsCount($user, $query)
    {
        switch ($user->role) {
            case 'responsable':
                return $query->clone()->where('statut', 'en_attente')->count();
            case 'archiviste':
                return $query->clone()->where('statut', 'approuvé_responsable')->count();
            default:
                return 0;
        }
    }
}