<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,archiviste')->except(['index', 'show']);
    }

    public function index()
    {
        $documents = Document::latest()->paginate(10);
        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        return view('documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'societe' => 'nullable|string|max:255',
            'direction' => 'nullable|string|max:255',
            'service' => 'nullable|string|max:255',
            'duree_max_retour' => 'required|integer|min:1',
        ]);

        Document::create([
            'titre' => $request->titre,
            'type' => $request->type,
            'societe' => $request->societe,
            'direction' => $request->direction,
            'service' => $request->service,
            'duree_max_retour' => $request->duree_max_retour,
            'statut' => 'disponible'
        ]);

        return redirect()->route('documents.index')->with('success', 'Document créé avec succès.');
    }

    public function show($id)
    {
        $document = Document::findOrFail($id);
        return view('documents.show', compact('document'));
    }

    public function edit($id)
    {
        $document = Document::findOrFail($id);
        return view('documents.edit', compact('document'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'societe' => 'nullable|string|max:255',
            'direction' => 'nullable|string|max:255',
            'service' => 'nullable|string|max:255',
            'duree_max_retour' => 'required|integer|min:1',
            'statut' => 'required|in:disponible,emprunté,archivé'
        ]);

        $document = Document::findOrFail($id);
        $document->update($request->all());

        return redirect()->route('documents.index')->with('success', 'Document mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        
        // Vérifier si le document est lié à des demandes
        if ($document->demandes()->count() > 0) {
            return back()->withErrors(['message' => 'Ce document est lié à des demandes et ne peut pas être supprimé.']);
        }
        
        $document->delete();
        return redirect()->route('documents.index')->with('success', 'Document supprimé avec succès.');
    }
}