<?php

namespace App\Http\Controllers;

use App\Models\DemandeDocument;
use App\Models\Document;
use App\Models\User;
use App\Models\Certificat;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\DemandeDocumentNotification;
use App\Mail\DemandeDocumentApprouvee;


class DemandeDocumentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role == 'admin') {
            $demandes = DemandeDocument::with(['utilisateur', 'document'])->latest()->paginate(10);
        } elseif ($user->role == 'responsable') {
            $demandes = DemandeDocument::where('idResponsableService', $user->idUtilisateur)
                ->orWhere('idUtilisateur', $user->idUtilisateur)
                ->with(['utilisateur', 'document'])
                ->latest()
                ->paginate(10);
        } elseif ($user->role == 'archiviste') {
            $demandes = DemandeDocument::where('statut', 'approuvé_responsable')
                ->orWhere('idArchiviste', $user->idUtilisateur)
                ->orWhere('idUtilisateur', $user->idUtilisateur)
                ->with(['utilisateur', 'document'])
                ->latest()
                ->paginate(10);
        } else {
            $demandes = DemandeDocument::where('idUtilisateur', $user->idUtilisateur)
                ->with(['document'])
                ->latest()
                ->paginate(10);
        }
        
        return view('demandes.index', compact('demandes'));
    }

    public function create()
    {
        $documents = Document::where('statut', 'disponible')->get();
        return view('demandes.create', compact('documents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idDocument' => 'required|exists:documents,idDocument',
            'description' => 'required|string'
        ]);

        $document = Document::findOrFail($request->idDocument);
        
        // Trouver un responsable du même service
        $responsable = User::where('role', 'responsable')
            ->where('service', $document->service)
            ->first();
        
        if (!$responsable) {
            return back()->withErrors(['message' => 'Aucun responsable trouvé pour ce service.']);
        }
        
        $demande = DemandeDocument::create([
            'idUtilisateur' => Auth::id(),
            'idResponsableService' => $responsable->idUtilisateur,
            'idDocument' => $request->idDocument,
            'description' => $request->description,
            'statut' => 'en_attente',
            'dateSoumission' => now()
        ]);
        
        // Créer une notification pour le responsable
        Notification::create([
            'idDestinataire' => $responsable->idUtilisateur,
            'message' => 'Nouvelle demande de document à approuver',
            'dateEnvoi' => now()
        ]);
        
        // Envoyer un email au responsable
        Mail::to($responsable->email)->send(new DemandeDocumentNotification($demande));
        
        return redirect()->route('demandes.index')->with('success', 'Demande soumise avec succès.');
    }

    public function approveByResponsable(Request $request, $id)
    {
        $demande = DemandeDocument::findOrFail($id);
        
        if (Auth::user()->idUtilisateur != $demande->idResponsableService) {
            return back()->withErrors(['message' => 'Vous n\'êtes pas autorisé à approuver cette demande.']);
        }
        
        // Trouver un archiviste
        $archiviste = User::where('role', 'archiviste')->first();
        
        if (!$archiviste) {
            return back()->withErrors(['message' => 'Aucun archiviste trouvé dans le système.']);
        }
        
        $demande->update([
            'statut' => 'approuvé_responsable',
            'dateValidationResponsable' => now(),
            'idArchiviste' => $archiviste->idUtilisateur
        ]);
        
        // Créer une notification pour l'archiviste
        Notification::create([
            'idDestinataire' => $archiviste->idUtilisateur,
            'message' => 'Nouvelle demande de document à valider',
            'dateEnvoi' => now()
        ]);
        
        // Envoyer un email à l'archiviste
        Mail::to($archiviste->email)->send(new DemandeDocumentNotification($demande));
        
        return redirect()->route('demandes.index')->with('success', 'Demande approuvée avec succès.');
    }

    public function rejectByResponsable(Request $request, $id)
    {
        $request->validate([
            'commentaire' => 'required|string'
        ]);
        
        $demande = DemandeDocument::findOrFail($id);
        
        if (Auth::user()->idUtilisateur != $demande->idResponsableService) {
            return back()->withErrors(['message' => 'Vous n\'êtes pas autorisé à rejeter cette demande.']);
        }
        
        $demande->update([
            'statut' => 'refusé_responsable',
            'dateValidationResponsable' => now()
        ]);
        
        // Créer une notification pour l'utilisateur
        Notification::create([
            'idDestinataire' => $demande->idUtilisateur,
            'message' => 'Votre demande de document a été refusée: ' . $request->commentaire,
            'dateEnvoi' => now()
        ]);
        
        return redirect()->route('demandes.index')->with('success', 'Demande refusée avec succès.');
    }
    public function approveByArchiviste(Request $request, $id)
    {
        $demande = DemandeDocument::findOrFail($id);
        
        if (Auth::user()->idUtilisateur != $demande->idArchiviste) {
            return back()->withErrors(['message' => 'Vous n\'êtes pas autorisé à approuver cette demande.']);
        }
        
        $demande->update([
            'statut' => 'approuvé_archiviste',
            'dateValidationArchiviste' => now()
        ]);
        
        // Créer un certificat d'approbation
        $certificat = Certificat::create([
            'idDemande' => $demande->idDemande,
            'idUtilisateur' => $demande->idUtilisateur,
            'idDocument' => $demande->idDocument,
            'dateGeneration' => now(),
            'signatureUtilisateur' => false
        ]);
        
        // Créer une notification pour l'utilisateur
        Notification::create([
            'idDestinataire' => $demande->idUtilisateur,
            'message' => 'Votre demande de document a été approuvée. Vous pouvez récupérer le document.',
            'dateEnvoi' => now()
        ]);
        
        // Envoyer un email à l'utilisateur
        Mail::to($demande->utilisateur->email)->send(new DemandeDocumentApprouvee($demande, $certificat));
        
        return redirect()->route('demandes.index')->with('success', 'Demande approuvée avec succès. Certificat généré.');
    }

    public function rejectByArchiviste(Request $request, $id)
    {
        $request->validate([
            'commentaire' => 'required|string'
        ]);
        
        $demande = DemandeDocument::findOrFail($id);
        
        if (Auth::user()->idUtilisateur != $demande->idArchiviste) {
            return back()->withErrors(['message' => 'Vous n\'êtes pas autorisé à rejeter cette demande.']);
        }
        
        $demande->update([
            'statut' => 'refusé_archiviste',
            'dateValidationArchiviste' => now()
        ]);
        
        // Créer une notification pour l'utilisateur
        Notification::create([
            'idDestinataire' => $demande->idUtilisateur,
            'message' => 'Votre demande de document a été refusée: ' . $request->commentaire,
            'dateEnvoi' => now()
        ]);
        
        return redirect()->route('demandes.index')->with('success', 'Demande refusée avec succès.');
    }
    public function markAsRetrieved(Request $request, $id)
    {
        $demande = DemandeDocument::findOrFail($id);
        
        if (Auth::user()->idUtilisateur != $demande->idArchiviste) {
            return back()->withErrors(['message' => 'Vous n\'êtes pas autorisé à marquer cette demande comme récupérée.']);
        }
        
        $request->validate([
            'signature' => 'required|accepted'
        ]);
        
        $demande->update([
            'statut' => 'récupéré',
            'dateRecuperation' => now()
        ]);
        
        // Mettre à jour le certificat
        $certificat = $demande->certificat;
        $certificat->update([
            'signatureUtilisateur' => true
        ]);
        
        // Mettre à jour le statut du document
        $document = $demande->document;
        $document->update([
            'statut' => 'emprunté'
        ]);
        
        return redirect()->route('demandes.index')->with('success', 'Document marqué comme récupéré avec succès.');
    }

    public function show($id)
    {
        $demande = DemandeDocument::with(['utilisateur', 'responsable', 'archiviste', 'document', 'certificat'])
            ->findOrFail($id);
        
        return view('demandes.show', compact('demande'));
    }
}
