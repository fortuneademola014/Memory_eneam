@extends('layouts.app')
@section('title','MémoBiblio – ENEAM')
@section('body')
<div class="relative min-h-screen overflow-y-auto bg-paper text-ink" x-data="{loginOpen: {{ $errors->any() ? 'true':'false' }}}">
  <section class="relative overflow-hidden">
    <div aria-hidden="true" class="pointer-events-none absolute -right-40 -top-40 size-[640px] rounded-full" style="background:radial-gradient(circle,rgba(185,119,14,0.14) 0%,rgba(185,119,14,0.05) 40%,rgba(185,119,14,0) 70%)"></div>
    <header class="relative mx-auto flex max-w-[1200px] items-center justify-between gap-4 px-5 py-5 md:px-8">
      <div class="flex items-center gap-2.5"><span class="flex size-9 items-center justify-center rounded-[8px] bg-navy font-serif text-lg font-bold text-white">M</span><span class="flex flex-col leading-none"><span class="font-serif text-lg font-semibold">MémoBiblio</span><span class="mt-1 text-[10px] font-semibold tracking-[0.14em] text-gold">ENEAM</span></span></div>
      <div class="flex flex-col items-end gap-1.5">
        <span class="hidden items-center gap-1.5 text-[12px] text-muted sm:flex"><svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>Réservé aux étudiants de l’ENEAM</span>
        <div class="flex items-center gap-2">
          @auth
            <a href="{{ route('catalogue') }}" class="inline-flex h-8 items-center rounded-[8px] bg-navy px-3 text-[13px] font-medium text-white hover:bg-navy-deep">Accéder au catalogue</a>
          @else
            <button @click="loginOpen=true" class="inline-flex h-8 items-center rounded-[8px] px-3 text-[13px] font-medium hover:bg-navy-soft">Se connecter</button>
            <a href="{{ route('register') }}" class="inline-flex h-8 items-center rounded-[8px] bg-navy px-3 text-[13px] font-medium text-white hover:bg-navy-deep">Créer un compte</a>
          @endauth
        </div>
      </div>
    </header>

    <div class="relative mx-auto grid max-w-[1200px] items-center gap-12 px-5 pb-20 pt-10 md:px-8 lg:grid-cols-[1.15fr_1fr] lg:pb-28 lg:pt-16">
      <div>
        <p class="mb-5 text-sm font-medium text-gold">Bibliothèque numérique · ENEAM</p>
        <h1 class="font-serif text-[40px] font-semibold leading-[1.08] tracking-tight text-ink sm:text-[56px]">Explorez les mémoires de l’ENEAM</h1>
        <p class="mt-5 max-w-xl text-[17px] leading-relaxed text-ink/70">Plus de mille travaux de fin d’études en finance, statistique, management et économie, validés par la bibliothèque et consultables en ligne.</p>
        <form action="{{ route('catalogue') }}" class="mt-9 flex flex-col gap-2 rounded-[14px] border border-line bg-surface p-2 shadow-lift sm:flex-row sm:items-center" role="search">
          <label class="flex flex-1 items-center gap-3 px-3">
            <svg class="size-5 shrink-0 text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
            <input name="q" aria-label="Rechercher un mémoire" placeholder="Rechercher un mémoire, un auteur, un mot-clé…" class="h-12 w-full bg-transparent text-[16px] placeholder:text-muted focus:outline-none">
          </label>
          <button class="inline-flex h-12 items-center justify-center rounded-[8px] bg-gold px-6 text-[15px] font-medium text-white hover:bg-gold-deep">Rechercher</button>
        </form>
        <div class="mt-4 flex flex-wrap items-center gap-2 text-[13px] text-muted">
          <span>Populaires :</span>
          @foreach(['mobile money','digitalisation','pauvreté','IA'] as $k)
            <a href="{{ route('catalogue',['q'=>$k]) }}" class="rounded-full border border-line bg-surface px-2.5 py-0.5 text-ink/75 hover:border-gold hover:text-gold-deep">{{ $k }}</a>
          @endforeach
        </div>
      </div>
      <div class="relative hidden h-[440px] lg:block" aria-hidden="true">
        @foreach($recent->take(3) as $i => $m)
          @php $pos = [['l'=>0,'t'=>30,'r'=>-6,'z'=>1],['l'=>90,'t'=>0,'r'=>4,'z'=>2],['l'=>40,'t'=>110,'r'=>-1.5,'z'=>3]][$i] ?? ['l'=>40,'t'=>40,'r'=>0,'z'=>1]; @endphp
          <a href="{{ route('memoires.show',$m) }}" class="absolute w-[300px] overflow-hidden rounded-[12px] border border-line bg-surface text-left shadow-pop transition-transform duration-200 hover:-translate-y-1" style="left:{{ $pos['l'] }}px;top:{{ $pos['t'] }}px;z-index:{{ $pos['z'] }};transform:rotate({{ $pos['r'] }}deg)">
            <div class="flex h-28 items-center justify-center text-white" style="background-image:linear-gradient(145deg,#1F4E79 0%,#1F4E79 45%,#6A5F3A 80%,#B9770E 100%)"><span class="font-serif text-3xl font-semibold">{{ $m->initials() }}</span></div>
            <div class="p-4"><p class="line-clamp-2 font-serif text-[15px] font-semibold leading-snug">{{ $m->title }}</p><p class="mt-2 text-[12px] text-muted">{{ $m->author->name }} · {{ $m->filiere->name }} • {{ $m->academicYear->label }}</p><p class="tabular mt-3 flex items-center gap-1 text-[12px] text-muted">{{ number_format($m->reads_count,0,',',' ') }} lectures</p></div>
          </a>
        @endforeach
      </div>
    </div>
  </section>

  <section class="border-y border-line bg-surface">
    <div class="mx-auto grid max-w-[1200px] grid-cols-1 divide-y divide-line px-5 sm:grid-cols-3 sm:divide-x sm:divide-y-0 md:px-8">
      @foreach([[$stats['memoirs'],'mémoires publiés'],[$stats['filieres'],'filières'],[$stats['reads'],'lectures']] as $s)
      <div class="flex flex-col items-center gap-1 py-8 text-center"><span class="tabular font-serif text-[44px] font-semibold leading-none text-navy">{{ number_format($s[0],0,',',' ') }}</span><span class="text-sm font-medium text-gold-deep">{{ $s[1] }}</span></div>
      @endforeach
    </div>
  </section>

  <section class="mx-auto max-w-[1200px] px-5 py-16 md:px-8">
    <div class="flex flex-wrap items-end justify-between gap-3"><h2 class="font-serif text-2xl font-semibold text-ink">Mémoires récents<span aria-hidden="true" class="mt-2 block h-[2px] w-10 rounded-full bg-gold"></span></h2><a href="{{ route('catalogue') }}" class="text-sm font-medium text-navy hover:underline">Voir tout le catalogue →</a></div>
    <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      @foreach($recent as $m)<x-memoir-card :memoir="$m" />@endforeach
    </div>
  </section>

  <footer class="border-t border-line bg-surface">
    <div class="mx-auto flex max-w-[1200px] flex-col gap-4 px-5 py-8 text-[13px] text-muted md:flex-row md:items-center md:justify-between md:px-8">
      <div class="flex items-center gap-2"><svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>Réservé aux étudiants et personnels de l’ENEAM</div>
      <div class="flex flex-wrap gap-x-6 gap-y-2"><span>Mentions légales</span><span>Charte de dépôt</span><span>Contact bibliothèque</span><span>© 2026 ENEAM · Université d’Abomey-Calavi</span></div>
    </div>
  </footer>

  <div x-cloak x-show="loginOpen" class="fixed inset-0 z-50 flex justify-end" aria-modal="true" role="dialog">
    <button @click="loginOpen=false" class="absolute inset-0 bg-ink/30" aria-label="Fermer"></button>
    <aside class="relative flex h-full w-full max-w-[440px] flex-col overflow-y-auto bg-surface p-8 shadow-pop">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2.5"><span class="flex size-8 items-center justify-center rounded-[8px] bg-navy font-serif font-bold text-white">M</span><span class="flex flex-col leading-none"><span class="font-serif text-[17px] font-semibold">MémoBiblio</span><span class="mt-1 text-[10px] font-semibold tracking-[0.14em] text-gold">ENEAM</span></span></div>
        <button @click="loginOpen=false" class="rounded-full p-2 text-muted hover:bg-paper hover:text-ink" aria-label="Fermer"><svg class="size-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg></button>
      </div>
      <h2 class="mt-12 font-serif text-3xl font-semibold">Bon retour parmi nous</h2>
      <p class="mt-2 text-sm text-muted">Connectez-vous avec votre matricule ou votre e-mail institutionnel.</p>
      @if($errors->any())
        <div role="alert" class="mt-6 flex gap-2.5 rounded-[8px] border border-st-rejected/20 bg-st-rejected/[0.06] p-3 text-[13px] text-[#B91C1C]"><svg class="mt-0.5 size-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>{{ $errors->first() }}</div>
      @endif
      <form method="POST" action="{{ route('login.store') }}" class="mt-6 flex flex-col gap-5 {{ $errors->any() ? 'animate-shake':'' }}">
        @csrf
        <div class="flex flex-col gap-1.5"><label for="login" class="text-sm font-medium">Matricule ou e-mail</label><input id="login" name="login" value="{{ old('login') }}" class="h-10 w-full rounded-[8px] border {{ $errors->has('login')?'border-st-rejected':'border-line' }} px-3 text-sm focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40" autocomplete="username"></div>
        <div class="flex flex-col gap-1.5" x-data="{show:false}"><label for="password" class="text-sm font-medium">Mot de passe</label><div class="relative"><input id="password" name="password" :type="show?'text':'password'" class="h-10 w-full rounded-[8px] border {{ $errors->any()?'border-st-rejected':'border-line' }} px-3 pr-10 text-sm focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40" autocomplete="current-password"><button type="button" @click="show=!show" class="absolute right-2 top-1/2 -translate-y-1/2 rounded p-1 text-muted hover:text-ink" :aria-label="show?'Masquer':'Afficher'"><svg class="size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg></button></div></div>
        <div class="flex items-center justify-between text-[13px]"><label class="flex items-center gap-2"><input type="checkbox" name="remember" class="size-4 rounded accent-navy" checked>Rester connecté</label><span class="font-medium text-navy">Mot de passe oublié ?</span></div>
        <button class="inline-flex h-12 w-full items-center justify-center rounded-[8px] bg-navy px-6 text-[15px] font-medium text-white hover:bg-navy-deep">Se connecter</button>
      </form>
      <p class="mt-6 text-center text-sm text-muted">Pas encore de compte ? <a href="{{ route('register') }}" class="font-medium text-navy hover:underline">Créer un compte</a></p>
    </aside>
  </div>
</div>
@endsection
