<?php

namespace App\Http\Controllers;

use App\Models\Certificat;
use App\Models\DemandeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show($id)
    {
        $certificat = Certificat::with(['demande', 'utilisateur', 'document'])->findOrFail($id);
        
        // Vérifier les permissions
        if (Auth::user()->role != 'admin' && 
            Auth::user()->idUtilisateur != $certificat->idUtilisateur && 
            Auth::user()->idUtilisateur != $certificat->demande->idResponsableService && 
            Auth::user()->idUtilisateur != $certificat->demande->idArchiviste) {
            return redirect()->route('home')->with('error', 'Vous n\'êtes pas autorisé à voir ce certificat.');
        }
        
        return view('certificats.show', compact('certificat'));
    }

    public function download($id)
    {
        $certificat = Certificat::with(['demande', 'utilisateur', 'document'])->findOrFail($id);
        
        // Vérifier les permissions
        if (Auth::user()->role != 'admin' && 
            Auth::user()->idUtilisateur != $certificat->idUtilisateur && 
            Auth::user()->idUtilisateur != $certificat->demande->idResponsableService && 
            Auth::user()->idUtilisateur != $certificat->demande->idArchiviste) {
            return redirect()->route('home')->with('error', 'Vous n\'êtes pas autorisé à télécharger ce certificat.');
        }
        
        $pdf = PDF::loadView('certificats.pdf', compact('certificat'));
        return $pdf->download('certificat-' . $certificat->idCertificat . '.pdf');
    }
}
