<?php

namespace App\Http\Controllers;

use App\Models\Emploi;
use App\Models\Entreprise;
use Illuminate\Http\Request;

class EmploiController extends Controller
{
    // Affiche la liste des emplois
    public function index()
    {
        $emplois = Emploi::with('entreprise')->get();
        return view('emploi', compact('emplois'));
    }

    // Affiche les détails d'un emploi
    public function show($id)
    {
        $emploi = Emploi::with('entreprise')->findOrFail($id);
        return view('emploi-details', compact('emploi'));
    }

    // Affiche le formulaire de création d'un emploi
    public function create()
    {
        $entreprises = Entreprise::all(); // Récupération des entreprises pour le formulaire
        return view('emploi-create', compact('entreprises'));
    }

    // Enregistre un nouvel emploi
    public function store(Request $request)
{
    $request->validate([
        'entreprise_id' => 'required|exists:entreprises,id',
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'location' => 'required|string|max:255',
        'salary' => 'required|numeric',
        'emploi_type' => 'required|string|max:50',
    ]);

    Emploi::create([
        'entreprise_id' => $request->entreprise_id,
        'title' => $request->title,
        'description' => $request->description,
        'location' => $request->location,
        'salary' => $request->salary,
        'emploi_type' => $request->emploi_type,
    ]);

    return redirect()->route('emplois.index')->with('success', 'Emploi ajouté avec succès.');
}


    // Affiche le formulaire d'édition d'un emploi
    public function edit($id)
    {
        $emploi = Emploi::findOrFail($id);
        $entreprises = Entreprise::all();
        return view('emploi-edit', compact('emploi', 'entreprises'));
    }

    // Met à jour un emploi
    public function update(Request $request, $id)
    {
        $request->validate([
            'entreprise_id' => 'required|exists:entreprises,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'salary' => 'required|numeric',
            'emploi_type' => 'required|string|max:50',
        ]);

        $emploi = Emploi::findOrFail($id);
        $emploi->update($request->all());

        return redirect()->route('emplois.index')->with('success', 'Emploi mis à jour.');
    }

    // Supprime un emploi
    public function destroy($id)
    {
        $emploi = Emploi::findOrFail($id);
        $emploi->delete();

        return redirect()->route('emplois.index')->with('success', 'Emploi supprimé.');
    }
}
