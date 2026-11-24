<?php

namespace App\Http\Controllers;

use App\Models\MemoirNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab','toutes');
        $q = MemoirNotification::where('user_id', Auth::id())->latest();
        if ($tab === 'non-lues') $q->whereNull('read_at');
        if ($tab === 'validations') $q->where('kind','valide');
        if ($tab === 'rejets') $q->where('kind','rejete');
        $notifications = $q->get();
        return view('notifications.index', compact('notifications','tab'));
    }

    public function markAllRead()
    {
        MemoirNotification::where('user_id', Auth::id())->whereNull('read_at')->update(['read_at'=>now()]);
        return back()->with('toast','Toutes les notifications ont été marquées comme lues.');
    }
}
