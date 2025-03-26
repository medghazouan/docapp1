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
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Http\Controllers\NotificationController;


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
        $services = Document::select('service')->distinct()->pluck('service');
        $sites = User::select('site')->distinct()->pluck('site');
        
        return view('demandes.create', compact('services', 'sites'));
    }

    public function store(Request $request)
{
    $request->validate([
        'service' => 'required|string',
        'site' => 'required|string',
        'idDocument' => 'required|exists:documents,idDocument',
        'description' => 'required|string'
    ]);

    $document = Document::findOrFail($request->idDocument);
    
    $admin = User::where('role', 'admin')->first();
    
    // Trouver un responsable du même service
    $responsable = User::where('role', 'responsable')
        ->where('service', $request->service)
        ->where('site', $request->site)
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
        'message' => 'Nouvelle demande de document "<strong>' . $document->titre . '</strong>" à approuver | Demandeur: <strong>' . Auth::user()->nom . '</strong>',
        'dateEnvoi' => now()
    ]);

    // Créer une notification pour l'admin
    Notification::create([
        'idDestinataire' => $admin->idUtilisateur,
        'message' => 'Nouvelle demande de document "<strong>' . $document->titre . '</strong>" en attente le traitement | Demandeur: <strong>' . Auth::user()->nom . '</strong> | Responsable: <strong>' . $responsable->nom . '</strong>',
        'dateEnvoi' => now()
    ]);
     
       // Envoyer un email au responsable
        Mail::to($responsable->email)->send(new DemandeDocumentNotification($demande));

        // Envoyer un email au admin
        Mail::to($admin->email)->send(new DemandeDocumentNotification($demande));
        return redirect()->route('demandes.index')->with('success', 'Demande soumise avec succès.');
    }

    public function approveByResponsable(Request $request, $id)
    {
        $demande = DemandeDocument::findOrFail($id);
        $admin =User::where('role', 'admin')
        ->first();
        
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
            'message' => 'Nouvelle demande de document à valider " <strong>' . $demande->document->titre . '</strong>" | Demandeur: <strong>' . User::find($demande->idUtilisateur)->nom . '</strong>',
            'dateEnvoi' => now()
        ]);

          // Créer une notification pour l'admin
          Notification::create([
            'idDestinataire' => $admin->idUtilisateur,
            'message' => 'la demande de document  "<strong>' . $demande->document->titre . '</strong>" 
            a envoyer a l archiviste : <strong>' . User::find($demande->idArchiviste)->nom . '</strong>
              | Demandeur: <strong>' . User::find($demande->idUtilisateur)->nom . '</strong>',
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
        $admin =User::where('role', 'admin')
        ->first();
        if (Auth::user()->idUtilisateur != $demande->idResponsableService) {
            return back()->withErrors(['message' => 'Vous n\'êtes pas autorisé à rejeter cette demande.']);
        }
        
        $demande->update([
            'statut' => 'refusé_responsable',
            'dateValidationResponsable' => now()
        ]);
        
        // Créer une notification pour l'utilisateur (refus)
        Notification::create([
            'idDestinataire' => $demande->idUtilisateur,
            'message' => 'Votre demande de document "<strong>' . $demande->document->titre . '</strong>" a été refusée: ' . $request->commentaire . ' | Responsable: <strong>' . User::find($demande->idResponsableService)->nom . '</strong>',
            'dateEnvoi' => now()
        ]);
        // Créer une notification pour l'admin (refus Responsable)
        Notification::create([
            'idDestinataire' => $admin->idUtilisateur,
            'message' => 'Une demande de document 
            "<strong>' . $demande->document->titre . '</strong>" a été refusée:  par  
             <strong>' . User::find($demande->idResponsableService)->nom . '</strong> | motif :
               <strong> ' . $request->commentaire . ' </strong>
             | Demandeur: <strong>' . User::find($demande->idUtilisateur)->nom . '</strong>',
            'dateEnvoi' => now()
        ]);
        
        return redirect()->route('demandes.index')->with('success', 'Demande refusée avec succès.');
    }
    public function approveByArchiviste(Request $request, $id)
{
    $demande = DemandeDocument::findOrFail($id);
    $admin =User::where('role', 'admin')
        ->first();
    // Vérification des autorisations
    if (Auth::user()->idUtilisateur != $demande->idArchiviste) {
        return redirect()->route('demandes.index')
            ->with('error', 'Action non autorisée');
    }

    // Validation unique et renforcée
    $validated = $request->validate([
        'dateRecuperation' => [
            'required', 
            'date', 
            'after_or_equal:' . now()->addHours(24)->format('Y-m-d\TH:i') // Délai de 24h minimum
        ]
    ]);

    // Mise à jour complète
    $demande->update([
        'statut' => 'approuvé_archiviste',
        'dateRecuperation' => $validated['dateRecuperation'],
        'dateValidationArchiviste' => now()

    ]);

    // Création du certificat avec gestion d'erreur
    try {
        $certificat = Certificat::create([
            'idDemande' => $demande->idDemande,
            'idUtilisateur' => $demande->idUtilisateur,
            'idDocument' => $demande->idDocument,
            'dateGeneration' => now(),
            'signatureUtilisateur' => false
        ]);
    } catch (\Exception $e) {
        Log::error("Erreur création certificat: " . $e->getMessage());
        return back()->with('error', 'Erreur lors de la génération du certificat');
    }
    // Notification pour l'utilisateur-> Approuve archiviste
    Notification::create([
        'idDestinataire' => $demande->idUtilisateur,
        'message' => 'Votre demande de document "<strong>' . $demande->document->titre . '</strong>" a été approuvée. Date de récupération: <strong>' 
                     . Carbon::parse($validated['dateRecuperation'])->translatedFormat('d F Y \à H\hi') 
                     . '</strong><br>Archivist : <strong>' . User::find($demande->idArchiviste)->nom . '</strong>',
        'dateEnvoi' => now()
    ]);
    // Notification pour de l'admin -> Approuve archiviste
    Notification::create([
        'idDestinataire' => $admin->idUtilisateur,
        'message' => 'La demande de document "<strong>' . $demande->document->titre . '</strong>" a été approuvée. par 
        <strong>' . User::find($demande->idArchiviste)->nom . '</strong>
         | Date de récupération: <strong>' 
                     . Carbon::parse($validated['dateRecuperation'])->translatedFormat('d F Y \à H\hi') 
                     . '</strong><br>Archivist : <strong>' . User::find($demande->idArchiviste)->nom . '</strong>',
        'dateEnvoi' => now()
    ]);

    // Décommenter pour l'envoi réel
     Mail::to($demande->utilisateur->email)->queue(new DemandeDocumentApprouvee($demande, $certificat));
    // Envoyer un email a l'admin
    Mail::to($admin->email)->send(new DemandeDocumentNotification($demande));
    return redirect()->route('demandes.index')
        ->with('success', 'Demande approuvée - Récupération prévue le ' 
              . Carbon::parse($validated['dateRecuperation'])->translatedFormat('d/m/Y à H\hi'));
}
    public function rejectByArchiviste(Request $request, $id)
    {
        $request->validate([
            'commentaire' => 'required|string'
        ]);
        
        $demande = DemandeDocument::findOrFail($id);
        $admin =User::where('role', 'admin')
        ->first();
        if (Auth::user()->idUtilisateur != $demande->idArchiviste) {
            return back()->withErrors(['message' => 'Vous n\'êtes pas autorisé à rejeter cette demande.']);
        }
        
        $demande->update([
            'statut' => 'refusé_archiviste',
            'dateValidationArchiviste' => now()
        ]);
        
        // Créer une notificationde reject archiviste pour l'utilisateur 
        Notification::create([
            'idDestinataire' => $demande->idUtilisateur,
            'message' => 'Votre demande de document "<strong>' . $demande->document->titre . '</strong>" a été refusée: ' . $request->commentaire 
                        . '| Archivist: <strong>' . User::find($demande->idArchiviste)->nom . '</strong>',
            'dateEnvoi' => now()
        ]);
        // Créer une notification de reject archiviste pour l'admin
        Notification::create([
            'idDestinataire' => $admin->idUtilisateur,
            'message' => 'Le demande de document "<strong>' . $demande->document->titre . '</strong>" a été refusée par 
            <strong>' . User::find($demande->idArchiviste)->nom . '</strong>
            | motif : <strong> ' . $request->commentaire . ' </strong>
              | Demandeur: <strong>' . User::find($demande->idUtilisateur)->nom . '</strong>',
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
        'signature' => 'required|accepted',
        'duree_max_retour' => 'required|integer|min:1'
    ]);
    
    // Calculate dateRetour based on dateRecuperation and duree_max_retour
    $dateRetour = Carbon::parse($demande->dateRecuperation)->addDays($request->duree_max_retour);
    
    $demande->update([
        'statut' => 'récupéré',
        'dateRecuperation' => now(),
        'dateRetour' => $dateRetour
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

    // Créer une notification pour l'utilisateur
    Notification::create([
        'idDestinataire' => $demande->idUtilisateur,
        'message' => "Le document <strong>{$document->titre}</strong> a été marqué comme récupéré. Date de retour prévue : <strong>" 
                     . $dateRetour->format('d/m/Y') . "</strong>",
        'dateEnvoi' => now()
    ]);

    // Créer une notification pour l'admin
    $adminUsers = User::where('role', 'admin')->pluck('idUtilisateur');
    foreach ($adminUsers as $adminId) {
        Notification::create([
            'idDestinataire' => $adminId,
            'message' => "Le document <strong>{$document->titre}</strong> a été récupéré par <strong>{$demande->utilisateur->nom}</strong>. Date de retour prévue : <strong>" 
                         . $dateRetour->format('d/m/Y') . "</strong>",
            'dateEnvoi' => now()
        ]);
    }
    
    return redirect()->route('demandes.index')->with('success', 'Document marqué comme récupéré avec succès.');
}

    public function show($id)
    {
        $demande = DemandeDocument::with(['utilisateur', 'responsable', 'archiviste', 'document', 'certificat'])
            ->findOrFail($id);
        
        return view('demandes.show', compact('demande'));
    }

    public function checkOverdueDocuments()
{
    // Find documents that are overdue
    $overdueDocuments = DemandeDocument::where('statut', 'récupéré')
        ->where('dateRetour', '<', now())
        ->with(['utilisateur', 'document', 'archiviste'])
        ->get();
    $overdueDocuments = DemandeDocument::overdue()->get();

    foreach ($overdueDocuments as $demande) {
        // Create notifications for user, archivist, and admin
        $notificationUsers = [
            $demande->idUtilisateur,
            $demande->idArchiviste
        ];

        // Find admin users
        $adminUsers = User::where('role', 'admin')->pluck('idUtilisateur');
        $notificationUsers = array_merge($notificationUsers, $adminUsers->toArray());

        foreach ($notificationUsers as $userId) {
            $notificationMessage = $this->createOverdueNotificationMessage($demande, $userId);
            
            Notification::create([
                'idDestinataire' => $userId,
                'message' => $notificationMessage,
                'dateEnvoi' => now()
            ]);
        }

        // Optional: Send email notifications
        //$this->sendOverdueDocumentEmails($demande);
    }
}

protected function createOverdueNotificationMessage($demande, $userId)
{
    $user = User::find($userId);
    
    if ($user->idUtilisateur == $demande->idUtilisateur) {
        return "Le document <strong>{$demande->document->titre}</strong> est en retard. Veuillez le retourner dès que possible.";
    } elseif ($user->role == 'archiviste') {
        return "Le document <strong>{$demande->document->titre}</strong> emprunté par <strong>{$demande->utilisateur->nom}</strong> est en retard.";
    } elseif ($user->role == 'admin') {
        return "Document en retard - <strong>{$demande->document->titre}</strong> emprunté par <strong>{$demande->utilisateur->nom}</strong>.";
    }
}

/*protected function sendOverdueDocumentEmails($demande)
{
    // Emails to user, archivist, and admin about overdue document
    Mail::to($demande->utilisateur->email)->send(new DemandeDocumentNotification($demande, 'user'));
    Mail::to($demande->archiviste->email)->send(new DemandeDocumentNotification($demande, 'archiviste'));
    
    $adminEmails = User::where('role', 'admin')->pluck('email');
    foreach ($adminEmails as $email) {
        Mail::to($email)->send(new DemandeDocumentNotification($demande, 'admin'));
    }
}*/

}
