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
        $unreadNotifications = Notification::where('idDestinataire', $user->idUtilisateur)
            ->where('read', false)
            ->count();
        
        // Initialize base query for statistics
        $statsQuery = DemandeDocument::query();
        
        if ($user->role == 'admin') {
            $documentTypes = Document::distinct()->pluck('type');
            $services = Document::distinct()->pluck('service');
            // Apply filters for admin
            $demandesQuery = DemandeDocument::with(['utilisateur', 'document']);
            
            // Filter by user name if provided
            if ($request->filled('utilisateur')) {
                $userName = $request->utilisateur;
                $demandesQuery->whereHas('utilisateur', function($query) use ($userName) {
                    $query->where('nom', 'like', '%' . $userName . '%');
                });
                $statsQuery->whereHas('utilisateur', function($query) use ($userName) {
                    $query->where('nom', 'like', '%' . $userName . '%');
                });
            }
            
            // Filter by date range if provided
            if ($request->filled('date_debut')) {
                $demandesQuery->where('dateSoumission', '>=', $request->date_debut);
                $statsQuery->where('dateSoumission', '>=', $request->date_debut);
            }
            
            if ($request->filled('date_fin')) {
                $demandesQuery->where('dateSoumission', '<=', $request->date_fin);
                $statsQuery->where('dateSoumission', '<=', $request->date_fin);
            }
            
            // Filter by status if provided
            if ($request->filled('statut')) {
                $demandesQuery->where('statut', $request->statut);
                $statsQuery->where('statut', $request->statut);
            }
            
            // Filter by document type if provided
            if ($request->filled('type')) {
                $documentType = $request->type;
                $demandesQuery->whereHas('document', function($query) use ($documentType) {
                    $query->where('type', $documentType);
                });
                $statsQuery->whereHas('document', function($query) use ($documentType) {
                    $query->where('type', $documentType);
                });
            }
            
            // Filter by service if provided
            if ($request->filled('service')) {
                $service = $request->service;
                $demandesQuery->whereHas('document', function($query) use ($service) {
                    $query->where('service', $service);
                });
                $statsQuery->whereHas('document', function($query) use ($service) {
                    $query->where('service', $service);
                });
            }
            
            // Get filtered demands for display
            $demandes = $demandesQuery->latest()->paginate(10);
            
            // Calculate statistics based on all filtered demands (not just the paginated ones)
            $stats = [
                'total_documents' => Document::count(),
                'total_demandes' => $statsQuery->count(),
                'mes_demandes' => DemandeDocument::where('idUtilisateur', $user->idUtilisateur)->count(),
                'demandes_en_attente' => $statsQuery->where('statut', 'en_attente')->count()
            ];
            
           

    // Calculate average processing time between dateRecuperation and dateSoumission
    $avgProcessingTime = DB::table('demande_documents')
        ->whereNotNull('dateRecuperation')
        ->whereNotNull('dateSoumission')
        ->when($request->filled('utilisateur'), function($query) use ($request) {
            $query->whereHas('utilisateur', function($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->utilisateur . '%');
            });
        })
        ->when($request->filled('date_debut'), function($query) use ($request) {
            $query->where('dateSoumission', '>=', $request->date_debut);
        })
        ->when($request->filled('date_fin'), function($query) use ($request) {
            $query->where('dateSoumission', '<=', $request->date_fin);
        })
        ->when($request->filled('statut'), function($query) use ($request) {
            $query->where('statut', $request->statut);
        })
        ->selectRaw('ROUND(AVG(TIMESTAMPDIFF(HOUR, dateSoumission, dateRecuperation)), 2) as avg_time')
        ->first();

    $stats['avg_processing_time'] = $avgProcessingTime && $avgProcessingTime->avg_time ? 
        $avgProcessingTime->avg_time : 0;

    // Also update the status distribution calculation:
    $statuses = ['en_attente', 'approuvé_responsable', 'refusé_responsable', 'approuvé_archiviste', 'refusé_archiviste', 'récupéré'];
    $stats['statusDistribution'] = [];
    foreach ($statuses as $status) {
        $stats['statusDistribution'][$status] = DemandeDocument::where('statut', $status)
            ->when($request->filled('utilisateur'), function($query) use ($request) {
                $query->whereHas('utilisateur', function($q) use ($request) {
                    $q->where('nom', 'like', '%' . $request->utilisateur . '%');
                });
            })
            ->when($request->filled('date_debut'), function($query) use ($request) {
                $query->where('dateSoumission', '>=', $request->date_debut);
            })
            ->when($request->filled('date_fin'), function($query) use ($request) {
                $query->where('dateSoumission', '<=', $request->date_fin);
            })
            ->count();
        }
            
        } elseif ($user->role == 'responsable') {
            $demandes = DemandeDocument::where('idResponsableService', $user->idUtilisateur)
                ->orWhere('idUtilisateur', $user->idUtilisateur)
                ->with(['utilisateur', 'document'])
                ->latest()
                ->paginate(10);
            
            // Base query for statistics
            $statsQuery = DemandeDocument::where('idResponsableService', $user->idUtilisateur)
                ->orWhere('idUtilisateur', $user->idUtilisateur);
            
            $stats = [
                'total_documents' => Document::count(),
                'total_demandes' => $statsQuery->count(),
                'mes_demandes' => DemandeDocument::where('idUtilisateur', $user->idUtilisateur)->count(),
                'demandes_en_attente' => $statsQuery->where('statut', 'en_attente')->count()
            ];
            
            // Calculate average processing time in hours
            $avgProcessingTime = $this->calculateAverageProcessingTime($statsQuery);
            $stats['avg_processing_time'] = $avgProcessingTime;
            
        } elseif ($user->role == 'archiviste') {
            $demandes = DemandeDocument::where('statut', 'approuvé_responsable')
                ->orWhere('idArchiviste', $user->idUtilisateur)
                ->orWhere('idUtilisateur', $user->idUtilisateur)
                ->with(['utilisateur', 'document'])
                ->latest()
                ->paginate(10);
            
            // Base query for statistics
            $statsQuery = DemandeDocument::where('statut', 'approuvé_responsable')
                ->orWhere('idArchiviste', $user->idUtilisateur)
                ->orWhere('idUtilisateur', $user->idUtilisateur);
            
            $stats = [
                'total_documents' => Document::count(),
                'total_demandes' => $statsQuery->count(),
                'mes_demandes' => DemandeDocument::where('idUtilisateur', $user->idUtilisateur)->count(),
                'demandes_en_attente' => $statsQuery->where('statut', 'approuvé_responsable')->count()
            ];
            
            
            
        } else {
            $demandes = DemandeDocument::where('idUtilisateur', $user->idUtilisateur)
                ->with(['document'])
                ->latest()
                ->paginate(10);
            
            // Base query for statistics
            $statsQuery = DemandeDocument::where('idUtilisateur', $user->idUtilisateur);
            
            $stats = [
                'total_documents' => Document::count(),
                'total_demandes' => DemandeDocument::count(),
                'mes_demandes' => $statsQuery->count(),
                'demandes_en_attente' => 0
            ];
            
           
        }
        
        if ($user->role == 'admin'){
            
            return view('home', compact('stats', 'unreadNotifications', 'demandes', 'documentTypes', 'services'));
        }
        else{
            return view('home', compact('stats', 'unreadNotifications', 'demandes'));
        }

        
    }
    
   
}