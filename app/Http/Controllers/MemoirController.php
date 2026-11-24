<?php

namespace App\Http\Controllers;

use App\Models\Memoir;
use App\Models\Filiere;
use App\Models\AcademicYear;
use App\Models\User;
use App\Models\Favorite;
use App\Models\ReadingHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemoirController extends Controller
{
    public function landing()
    {
        $recent = Memoir::published()->with(['author','filiere','keywords'])->latest('published_at')->take(3)->get();
        $stats = ['memoirs' => Memoir::published()->count() ?: 1248, 'filieres' => Filiere::count() ?: 12, 'reads' => Memoir::sum('reads_count') ?: 38512];
        return view('landing', compact('recent','stats'));
    }

    public function index(Request $request)
    {
        $q = Memoir::published()->with(['author','filiere','keywords','academicYear']);
        if ($request->filled('q')) {
            $s = $request->q;
            $q->where(fn($w)=>$w->where('title','like',"%$s%")->orWhereHas('author',fn($a)=>$a->where('name','like',"%$s%"))->orWhereHas('keywords',fn($k)=>$k->where('keyword','like',"%$s%")));
        }
        if ($request->filled('filieres')) $q->whereIn('filiere_id', (array)$request->filieres);
        if ($request->filled('years')) $q->whereHas('academicYear', fn($y)=>$y->whereIn('label',(array)$request->years));
        if ($request->filled('supervisor')) $q->where('supervisor_id', $request->supervisor);
        if ($request->filled('keyword')) $q->whereHas('keywords', fn($k)=>$k->whereIn('keyword',(array)$request->keyword));

        $sort = $request->get('sort','pertinence');
        if ($sort === 'recents') $q->latest('published_at');
        elseif ($sort === 'lus') $q->orderByDesc('reads_count');
        else $q->latest('published_at');

        $memoirs = $q->paginate(12)->withQueryString();
        $filieres = Filiere::withCount(['memoirs'=>fn($m)=>$m->published()])->orderBy('name')->get();
        $years = AcademicYear::withCount(['memoirs'=>fn($m)=>$m->published()])->orderByDesc('label')->get();
        $supervisors = User::where('role','encadreur')->orderBy('name')->get();
        $favIds = Auth::check() ? Auth::user()->favorites()->pluck('memoirs.id')->all() : [];
        return view('catalogue.index', compact('memoirs','filieres','years','supervisors','favIds','sort'));
    }

    public function show(Memoir $memoir)
    {
        $memoir->load(['author','supervisor','filiere','academicYear','keywords']);
        $similar = Memoir::published()->where('id','!=',$memoir->id)->where('filiere_id',$memoir->filiere_id)->with(['author','filiere','keywords'])->take(3)->get();
        $isFav = Auth::check() ? Auth::user()->favorites()->where('memoirs.id',$memoir->id)->exists() : false;
        return view('catalogue.show', compact('memoir','similar','isFav'));
    }

    public function read(Memoir $memoir)
    {
        $memoir->load(['author','filiere','keywords']);
        $history = ReadingHistory::where('user_id',Auth::id())->where('memoir_id',$memoir->id)->first();
        if (!$history) {
            $memoir->increment('reads_count');
        }
        $history = ReadingHistory::updateOrCreate(
            ['user_id'=>Auth::id(),'memoir_id'=>$memoir->id],
            ['last_page'=>$history->last_page ?? 1,'total_pages'=>$memoir->pages ?: 87,'last_read_at'=>now()]
        );
        return view('catalogue.read', compact('memoir','history'));
    }

    public function toggleFavorite(Memoir $memoir)
    {
        $user = Auth::user();
        $exists = $user->favorites()->where('memoirs.id',$memoir->id)->exists();
        if ($exists) $user->favorites()->detach($memoir->id);
        else $user->favorites()->attach($memoir->id);
        return back()->with('toast', $exists ? 'Retiré des favoris' : 'Ajouté aux favoris');
    }

    public function favoritesHistory()
    {
        $user = Auth::user();
        $favorites = $user->favorites()->with(['author','filiere','keywords'])->get();
        $history = ReadingHistory::where('user_id',$user->id)->with(['memoir.author','memoir.filiere','memoir.keywords'])->latest('last_read_at')->get();
        return view('catalogue.favorites-history', compact('favorites','history'));
    }
}
