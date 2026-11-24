<?php

namespace App\Http\Controllers;

use App\Models\Memoir;
use App\Models\MemoirNotification;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab','a-verifier');
        $base = Memoir::with(['author','filiere','academicYear','keywords'])->latest('submitted_at');
        $counts = [
            'a-verifier' => (clone $base)->where('status','soumis')->count(),
            'en-cours' => (clone $base)->where('status','verification')->count(),
            'traites' => (clone $base)->whereIn('status',['valide','publie','rejete'])->count(),
        ];
        $stats = [
            'A vérifier' => Memoir::where('status','soumis')->count(),
            'En cours' => Memoir::where('status','verification')->count(),
            'Validés ce mois' => Memoir::whereIn('status',['valide','publie'])->whereMonth('validated_at', now()->month)->count(),
            'Rejetés ce mois' => Memoir::where('status','rejete')->whereMonth('updated_at', now()->month)->count(),
        ];
        $memoirs = match($tab) {
            'en-cours' => (clone $base)->where('status','verification')->get(),
            'traites' => (clone $base)->whereIn('status',['valide','publie','rejete'])->get(),
            default => (clone $base)->where('status','soumis')->get(),
        };
        return view('validation.index', compact('memoirs','tab','counts','stats'));
    }

    public function show(Memoir $memoir)
    {
        $memoir->load(['author','filiere','academicYear','keywords','supervisor']);
        if ($memoir->status === 'soumis') $memoir->update(['status'=>'verification']);
        return view('validation.show', compact('memoir'));
    }

    public function decide(Request $request, Memoir $memoir)
    {
        $data = $request->validate([
            'decision' => ['required','in:valider,rejeter'],
            'rejection_reason' => ['required_if:decision,rejeter','nullable','string','max:1000'],
            'embargo_until' => ['nullable','date'],
            'download_disabled' => ['nullable','boolean'],
            'restricted_access' => ['nullable','boolean'],
        ]);
        if ($data['decision'] === 'valider') {
            $memoir->update([
                'status'=>'valide','validated_at'=>now(),'published_at'=>now(),'rejection_reason'=>null,
                'embargo_until'=>$data['embargo_until'] ?? null,
                'download_disabled'=>$request->boolean('download_disabled', true),
                'restricted_access'=>$request->boolean('restricted_access', false),
            ]);
            MemoirNotification::create([
                'user_id'=>$memoir->author_id,'memoir_id'=>$memoir->id,'kind'=>'valide','title'=>'Votre mémoire a été validé',
                'message'=>"« {$memoir->title} » est désormais publié dans le catalogue.",
                'action_label'=>'Voir le mémoire','action_url'=>route('memoires.show',$memoir),
            ]);
            $toast = 'Mémoire validé avec succès.';
        } else {
            $memoir->update(['status'=>'rejete','rejection_reason'=>$data['rejection_reason']]);
            MemoirNotification::create([
                'user_id'=>$memoir->author_id,'memoir_id'=>$memoir->id,'kind'=>'rejete','title'=>'Dépôt à corriger',
                'message'=>"Le validateur demande des corrections sur « {$memoir->title} ». Motif : {$data['rejection_reason']}",
                'action_label'=>'Voir le motif','action_url'=>route('deposits.index'),
            ]);
            $toast = 'Décision enregistrée.';
        }
        return redirect()->route('validation.index')->with('toast',$toast);
    }
}
