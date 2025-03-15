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

    public function index()
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
        
        if ($user->role == 'responsable') {
            $stats['demandes_en_attente'] = DemandeDocument::where('idResponsableService', $user->idUtilisateur)
                ->where('statut', 'en_attente')
                ->count();
        } elseif ($user->role == 'archiviste') {
            $stats['demandes_en_attente'] = DemandeDocument::where('idArchiviste', $user->idUtilisateur)
                ->where('statut', 'approuvé_responsable')
                ->count();
        }
        
        return view('home', compact('stats', 'unreadNotifications'));
    }
}
