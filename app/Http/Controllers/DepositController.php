<?php

namespace App\Http\Controllers;

use App\Models\Memoir;
use App\Models\MemoirKeyword;
use App\Models\Filiere;
use App\Models\AcademicYear;
use App\Models\User;
use App\Models\MemoirNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    public function index()
    {
        $memoirs = Memoir::where('author_id', Auth::id())->with(['filiere','academicYear','keywords'])->latest()->get();
        return view('deposits.index', compact('memoirs'));
    }

    public function create()
    {
        return view('deposits.create', [
            'filieres' => Filiere::orderBy('name')->get(),
            'years' => AcademicYear::orderByDesc('label')->get(),
            'supervisors' => User::where('role','encadreur')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'abstract' => ['required','string','min:30'],
            'keywords' => ['required','string'],
            'filiere_id' => ['required','exists:filieres,id'],
            'academic_year_id' => ['required','exists:academic_years,id'],
            'supervisor_id' => ['required','exists:users,id'],
            'pdf' => ['nullable','file','mimes:pdf','max:30720'],
            'certify' => ['accepted'],
        ]);
        $pdfPath = $request->file('pdf')?->store('memoirs','public');
        $memoir = Memoir::create([
            'author_id'=>Auth::id(),'supervisor_id'=>$data['supervisor_id'],'filiere_id'=>$data['filiere_id'],
            'academic_year_id'=>$data['academic_year_id'],'title'=>$data['title'],'abstract'=>$data['abstract'],
            'pdf_path'=>$pdfPath,'pages'=>rand(60,120),'status'=>'soumis','submitted_at'=>now(),'download_disabled'=>true,
        ]);
        foreach (array_filter(array_map('trim', explode(',', $data['keywords']))) as $kw) {
            MemoirKeyword::create(['memoir_id'=>$memoir->id,'keyword'=>$kw]);
        }
        MemoirNotification::create([
            'user_id'=>Auth::id(),'memoir_id'=>$memoir->id,'kind'=>'soumis','title'=>'Dépôt reçu',
            'message'=>'Votre mémoire a bien été soumis. Un validateur de la bibliothèque le prendra en charge sous 5 jours ouvrés.',
            'action_label'=>'Suivre le dépôt','action_url'=>route('deposits.index'),
        ]);
        return redirect()->route('deposits.index')->with('toast','Mémoire soumis avec succès');
    }
}
