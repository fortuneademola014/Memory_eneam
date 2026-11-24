@extends('layouts.app')
@php
  $user = auth()->user();
  $role = $user->role ?? 'lecteur';
  $unread = $user ? $user->memoirNotifications()->whereNull('read_at')->count() : 0;
  $active = $active ?? '';
  $navGroups = [
    ['label'=>'CATALOGUE','items'=>[
      ['id'=>'accueil','label'=>'Accueil','route'=>route('landing'),'icon'=>'M3 12l9-9 9 9M4.5 9.75V21h15V9.75'],
      ['id'=>'catalogue','label'=>'Catalogue','route'=>route('catalogue'),'icon'=>'M12 21v-8.25M3 9l9-6 9 6v11.25a.75.75 0 01-.75.75H3.75a.75.75 0 01-.75-.75V9z'],
      ['id'=>'favoris','label'=>'Favoris','route'=>route('favorites.history'),'icon'=>'M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z'],
      ['id'=>'historique','label'=>'Historique','route'=>route('favorites.history').'#historique','icon'=>'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z'],
    ]],
    ['label'=>'MON ESPACE','roles'=>['etudiant','admin'],'items'=>[
      ['id'=>'depots','label'=>'Mes dépôts','route'=>route('deposits.index'),'icon'=>'M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75M3.75 6A2.25 2.25 0 016 3.75h4.19c.398 0 .78.158 1.06.44l2.12 2.12c.282.281.663.44 1.061.44h4.819A2.25 2.25 0 0121.5 9v9A2.25 2.25 0 0119.25 20.25H4.75A2.25 2.25 0 012.5 18V6'],
      ['id'=>'nouveau','label'=>'Nouveau dépôt','route'=>route('deposits.create'),'icon'=>'M12 4.5v15m7.5-7.5h-15'],
    ]],
    ['label'=>'VALIDATION','roles'=>['validateur','admin'],'items'=>[
      ['id'=>'validation','label'=>'File de validation','route'=>route('validation.index'),'badge'=>\App\Models\Memoir::where('status','soumis')->count(),'icon'=>'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z'],
    ]],
    ['label'=>'ADMINISTRATION','roles'=>['admin'],'items'=>[
      ['id'=>'utilisateurs','label'=>'Utilisateurs','route'=>route('admin.index',['tab'=>'utilisateurs']),'icon'=>'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z'],
      ['id'=>'filieres','label'=>'Filières','route'=>route('admin.index',['tab'=>'filieres']),'icon'=>'M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342'],
      ['id'=>'annees','label'=>'Années','route'=>route('admin.index',['tab'=>'annees']),'icon'=>'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5'],
      ['id'=>'statistiques','label'=>'Statistiques','route'=>route('admin.index',['tab'=>'statistiques']),'icon'=>'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z'],
    ]],
  ];
@endphp
@section('body')
<div class="flex h-screen flex-col bg-paper text-ink" x-data="{notifOpen:false,menuOpen:false}">
  <header class="relative z-30 flex h-16 shrink-0 items-center gap-3 border-b border-line bg-surface px-4 md:gap-6 md:px-6">
    <a href="{{ route('landing') }}" class="shrink-0 flex items-center gap-2.5" aria-label="Accueil MémoBiblio">
      <span class="flex size-8 items-center justify-center rounded-[8px] bg-navy font-serif text-base font-bold text-white">M</span>
      <span class="flex flex-col leading-none"><span class="font-serif text-[17px] font-semibold text-ink">MémoBiblio</span><span class="mt-1 text-[10px] font-semibold tracking-[0.14em] text-gold">ENEAM</span></span>
    </a>
    <div class="mx-auto hidden w-full max-w-xl md:block">
      <form action="{{ route('catalogue') }}" class="relative flex items-center">
        <svg class="pointer-events-none absolute left-3 size-4 text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
        <input name="q" value="{{ request('q') }}" aria-label="Recherche globale" placeholder="Rechercher un mémoire, un auteur, un mot-clé…" class="h-10 w-full rounded-[8px] border border-line bg-paper pl-9 pr-14 text-sm placeholder:text-muted focus:border-gold focus:bg-surface focus:outline-none focus:ring-2 focus:ring-gold/30">
        <kbd class="pointer-events-none absolute right-2.5 rounded border border-line bg-surface px-1.5 py-0.5 font-sans text-[11px] text-muted">⌘K</kbd>
      </form>
    </div>
    <div class="ml-auto flex items-center gap-1 md:ml-0 md:gap-2">
      @auth
      <div class="relative">
        <button @click="notifOpen=!notifOpen;menuOpen=false" aria-label="Notifications" class="relative flex size-10 items-center justify-center rounded-full text-muted hover:bg-navy-soft hover:text-navy">
          <svg class="size-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
          @if($unread)<span class="tabular absolute right-1.5 top-1.5 flex size-4 items-center justify-center rounded-full bg-gold text-[10px] font-bold text-white ring-2 ring-surface">{{ $unread }}</span>@endif
        </button>
        <div x-cloak x-show="notifOpen" @click.outside="notifOpen=false" class="absolute -right-14 top-12 w-[min(380px,calc(100vw-2rem))] overflow-hidden rounded-[12px] border border-line bg-surface shadow-pop sm:right-0">
          <div class="flex items-center justify-between border-b border-line px-4 py-3"><p class="font-serif text-base font-semibold">Notifications</p>
            <form method="POST" action="{{ route('notifications.readAll') }}">@csrf<button class="text-[13px] font-medium text-navy hover:underline">Tout marquer comme lu</button></form></div>
          <ul class="max-h-[360px] overflow-y-auto">
            @forelse($user->memoirNotifications()->latest()->take(5)->get() as $n)
            <li class="relative flex gap-3 border-b border-line-soft px-4 py-3 last:border-0 hover:bg-paper">
              @if(!$n->read_at)<span class="absolute left-1.5 top-5 size-1.5 rounded-full bg-gold"></span>@endif
              <span class="flex size-8 shrink-0 items-center justify-center rounded-full {{ $n->kind==='valide'?'bg-st-valid/10 text-st-valid':($n->kind==='rejete'?'bg-st-rejected/10 text-st-rejected':'bg-navy-soft text-navy') }}"><svg class="size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg></span>
              <div class="min-w-0 flex-1"><p class="text-[13px] {{ !$n->read_at?'font-semibold':'' }}">{{ $n->title }}</p><p class="mt-0.5 line-clamp-1 text-[12px] text-muted">{{ $n->message }}</p><p class="mt-1 text-[11px] text-muted">{{ $n->created_at->diffForHumans() }}</p></div>
            </li>
            @empty
            <li class="px-4 py-6 text-center text-sm text-muted">Aucune notification.</li>
            @endforelse
          </ul>
          <a href="{{ route('notifications.index') }}" class="block border-t border-line py-3 text-center text-[13px] font-medium text-navy hover:bg-paper">Voir toutes les notifications</a>
        </div>
      </div>
      <div class="relative">
        <button @click="menuOpen=!menuOpen;notifOpen=false" class="flex items-center gap-2 rounded-full p-1 pr-2 hover:bg-navy-soft">
          <span class="inline-flex size-8 items-center justify-center rounded-full {{ $role==='admin'?'bg-gold-soft text-gold-deep':'bg-navy-soft text-navy' }} text-[12px] font-semibold">{{ $user->initials() }}</span>
          <span class="hidden text-left leading-tight lg:block"><span class="block text-[13px] font-medium">{{ $user->name }}</span><span class="block text-[11px] text-muted">{{ ucfirst($role) }}</span></span>
        </button>
        <div x-cloak x-show="menuOpen" @click.outside="menuOpen=false" class="absolute right-0 top-12 w-56 rounded-[12px] border border-line bg-surface p-1.5 shadow-pop">
          <a href="{{ route('deposits.index') }}" class="block rounded-[8px] px-3 py-2 text-sm hover:bg-navy-soft">Mon espace</a>
          <a href="{{ route('favorites.history') }}" class="block rounded-[8px] px-3 py-2 text-sm hover:bg-navy-soft">Mes favoris</a>
          <a href="{{ route('favorites.history') }}#historique" class="block rounded-[8px] px-3 py-2 text-sm hover:bg-navy-soft">Historique</a>
          <div class="my-1 h-px bg-line"></div>
          <form method="POST" action="{{ route('logout') }}">@csrf<button class="w-full rounded-[8px] px-3 py-2 text-left text-sm text-st-rejected hover:bg-st-rejected/5">Déconnexion</button></form>
        </div>
      </div>
      @else
      <a href="{{ route('login') }}" class="hidden sm:inline-flex h-8 items-center rounded-[8px] px-3 text-[13px] font-medium hover:bg-navy-soft">Se connecter</a>
      <a href="{{ route('register') }}" class="inline-flex h-8 items-center rounded-[8px] bg-navy px-3 text-[13px] font-medium text-white hover:bg-navy-deep">Créer un compte</a>
      @endauth
    </div>
  </header>

  <div class="flex min-h-0 flex-1">
    <aside class="hidden w-[72px] shrink-0 flex-col border-r border-line bg-surface md:flex lg:w-[260px]">
      <nav class="flex-1 overflow-y-auto px-3 py-5">
        @foreach($navGroups as $g)
          @if(empty($g['roles']) || ($user && in_array($role,$g['roles'])))
          <div class="mb-6">
            <p class="mb-2 hidden px-3 text-[11px] font-semibold tracking-[0.08em] text-muted lg:block">{{ $g['label'] }}</p>
            <ul class="flex flex-col gap-0.5">
              @foreach($g['items'] as $it)
                @php $on = $active === $it['id']; @endphp
                <li><a href="{{ $it['route'] }}" @if($on) aria-current="page" @endif class="relative flex h-10 items-center justify-center gap-3 rounded-[8px] px-3 text-sm lg:justify-start {{ $on ? 'bg-navy/[0.08] font-semibold text-navy' : 'text-ink/75 hover:bg-paper hover:text-ink' }}">
                  @if($on)<span class="absolute -left-3 top-1.5 bottom-1.5 w-[3px] rounded-r-full bg-gold"></span>@endif
                  <svg class="size-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $it['icon'] }}"/></svg>
                  <span class="hidden truncate lg:inline">{{ $it['label'] }}</span>
                  @if(!empty($it['badge']))<span class="tabular ml-auto hidden rounded-full bg-gold-soft px-1.5 text-[11px] font-semibold text-gold-deep lg:inline">{{ $it['badge'] }}</span>@endif
                </a></li>
              @endforeach
            </ul>
          </div>
          @endif
        @endforeach
      </nav>
    </aside>
    <main class="min-w-0 flex-1 overflow-y-auto pb-20 md:pb-0">@yield('content')</main>
  </div>

  <nav class="fixed inset-x-0 bottom-0 z-30 grid grid-cols-5 border-t border-line bg-surface md:hidden">
    @foreach([['accueil','Accueil',route('landing'),'M3 12l9-9 9 9M4.5 9.75V21h15V9.75'],['catalogue','Catalogue',route('catalogue'),'M12 21v-8.25M3 9l9-6 9 6v11.25a.75.75 0 01-.75.75H3.75a.75.75 0 01-.75-.75V9z'],['favoris','Favoris',route('favorites.history'),'M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z'],['depots','Dépôts',route('deposits.index'),'M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75M3.75 6A2.25 2.25 0 016 3.75h4.19c.398 0 .78.158 1.06.44l2.12 2.12c.282.281.663.44 1.061.44h4.819A2.25 2.25 0 0121.5 9v9A2.25 2.25 0 0119.25 20.25H4.75A2.25 2.25 0 012.5 18V6'],['notifications','Alertes',route('notifications.index'),'M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0']] as $m)
      @php $on = $active === $m[0]; @endphp
      <a href="{{ $m[2] }}" class="flex flex-col items-center gap-0.5 py-2 text-[11px] {{ $on ? 'font-semibold text-navy':'text-muted' }}"><svg class="size-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $m[3] }}"/></svg>{{ $m[1] }}</a>
    @endforeach
  </nav>
</div>
@endsection
