<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Memoir;
use App\Models\Filiere;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab','utilisateurs');
        $stats = [
            'Mémoires totaux' => Memoir::count(),
            'Utilisateurs actifs' => User::count(),
            'Consultations du mois' => Memoir::sum('reads_count'),
            'Taux de validation' => (Memoir::count() ? round(Memoir::whereIn('status',['valide','publie'])->count()/Memoir::count()*100) : 0) . '%',
        ];
        $users = User::with('filiere')->orderBy('name')->get();
        $filieres = Filiere::withCount('memoirs')->orderBy('name')->get();
        $years = AcademicYear::withCount('memoirs')->orderByDesc('label')->get();
        $topMemoirs = Memoir::with('author')->orderByDesc('reads_count')->take(5)->get();
        $byFiliere = Filiere::withCount(['memoirs'=>fn($q)=>$q->whereIn('status',['valide','publie'])])->get();
        return view('admin.index', compact('tab','stats','users','filieres','years','topMemoirs','byFiliere'));
    }

    public function storeFiliere(Request $request)
    {
        $data = $request->validate(['name'=>['required','string','max:255']]);
        Filiere::create(['name'=>$data['name'],'slug'=>Str::slug($data['name'])]);
        return back()->with('toast','Filière ajoutée.');
    }

    public function storeYear(Request $request)
    {
        $data = $request->validate(['label'=>['required','string','max:20','unique:academic_years,label']]);
        AcademicYear::create(['label'=>$data['label'],'is_current'=>$request->boolean('is_current')]);
        return back()->with('toast','Année académique ajoutée.');
    }

    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate(['role'=>['required','in:etudiant,encadreur,validateur,admin,lecteur']]);
        $user->update(['role'=>$data['role']]);
        return back()->with('toast','Rôle mis à jour.');
    }
}
