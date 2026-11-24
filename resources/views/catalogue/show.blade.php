@extends('layouts.shell')
@section('title', $memoir->title.' – MémoBiblio')
@section('content')
<div class="mx-auto max-w-[1240px] px-4 py-6 md:px-8 md:py-8">
  <nav aria-label="Fil d’Ariane" class="flex min-w-0 items-center gap-1.5 text-[13px] text-muted">
    <a href="{{ route('catalogue') }}" class="shrink-0 hover:text-navy">Catalogue</a><span>/</span><span class="shrink-0">Mémoires</span><span>/</span><span class="truncate text-ink">{{ $memoir->title }}</span>
  </nav>
  <div class="mt-6 grid gap-8 lg:grid-cols-[minmax(0,65fr)_minmax(300px,35fr)] lg:gap-10">
    <article class="min-w-0">
      <div class="flex flex-wrap gap-2">
        <x-status-badge :status="$memoir->status === 'publie' ? 'valide' : $memoir->status" />
        @if($memoir->restricted_access)<x-status-badge status="restreint" />@endif
        @if($memoir->download_disabled)<span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium" style="color:#4B5563;background:#6B72801A"><svg class="size-3" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>Téléchargement désactivé</span>@endif
      </div>
      <h1 class="mt-4 font-serif text-[32px] font-semibold leading-[1.15] tracking-tight md:text-[40px]">{{ $memoir->title }}</h1>
      <div class="mt-5 flex flex-wrap items-center gap-x-3 gap-y-2 text-sm text-muted">
        <span class="flex items-center gap-2 font-medium text-ink"><span class="inline-flex size-7 items-center justify-center rounded-full bg-navy-soft text-[11px] font-semibold text-navy">{{ $memoir->author->initials() }}</span>{{ $memoir->author->name }}</span>
        <span>•</span><span>{{ $memoir->filiere->name }}</span><span>•</span><span>Année {{ $memoir->academicYear->label }}</span><span>•</span><span>Encadreur : {{ $memoir->supervisor->name ?? '—' }}</span>
      </div>
      <section class="mt-8 rounded-[12px] border border-line border-l-[3px] border-l-gold bg-surface p-6 md:p-7">
        <h2 class="text-[13px] font-semibold text-gold-deep">Résumé</h2>
        <p class="mt-3 font-serif text-[17px] leading-[1.75] text-ink/90">{{ $memoir->abstract }}</p>
      </section>
      <section class="mt-8">
        <h2 class="text-sm font-semibold">Mots-clés</h2>
        <div class="mt-3 flex flex-wrap gap-2">@foreach($memoir->keywords as $kw)<span class="inline-flex rounded-full border border-line bg-paper px-3 py-1 text-[13px] text-ink/75">{{ $kw->keyword }}</span>@endforeach</div>
      </section>
      <section class="mt-10">
        <h2 class="font-serif text-xl font-semibold">Informations</h2>
        <dl class="mt-4 grid gap-x-8 border-t border-line sm:grid-cols-2">
          @foreach([['Filière',$memoir->filiere->name],['Année académique',$memoir->academicYear->label],['Encadreur',$memoir->supervisor->name ?? '—'],['Date de publication',optional($memoir->published_at)->translatedFormat('d F Y') ?? '—'],['Nombre de pages',$memoir->pages.' pages'],['Embargo',$memoir->embargo_until ? 'Jusqu’au '.$memoir->embargo_until->translatedFormat('d F Y') : 'Aucun']] as [$k,$v])
          <div class="flex justify-between gap-4 border-b border-line-soft py-3 text-sm"><dt class="text-muted">{{ $k }}</dt><dd class="text-right font-medium {{ $k==='Embargo' && $memoir->embargo_until ? 'text-st-embargo':'text-ink' }}">{{ $v }}</dd></div>
          @endforeach
        </dl>
      </section>
    </article>

    <aside class="lg:sticky lg:top-6 lg:self-start">
      <div class="rounded-[12px] border border-line bg-surface p-5 shadow-rest">
        <div class="tabular flex items-center justify-between text-[13px] text-muted"><span>{{ number_format($memoir->reads_count,0,',',' ') }} lectures</span><span>{{ $memoir->pages }} pages · PDF</span></div>
        <div class="mt-4 flex flex-col gap-2">
          <a href="{{ route('memoires.read',$memoir) }}" class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-[8px] bg-navy text-[15px] font-medium text-white hover:bg-navy-deep"><svg class="size-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>Lire en ligne</a>
          @auth
          <form method="POST" action="{{ route('memoires.favorite',$memoir) }}">@csrf<button class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-[8px] border border-line text-[15px] font-medium hover:bg-navy-soft/50"><svg class="size-[18px] {{ $isFav?'fill-st-rejected text-st-rejected':'' }}" fill="{{ $isFav?'currentColor':'none' }}" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>{{ $isFav ? 'Dans vos favoris' : 'Ajouter aux favoris' }}</button></form>
          @endauth
        </div>
        @if($memoir->download_disabled)
        <div class="mt-4 flex gap-2.5 rounded-[8px] border border-st-review/25 bg-st-review/[0.07] p-3 text-[13px] text-[#92400E]"><svg class="mt-0.5 size-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg><span><span class="block font-semibold">Téléchargement désactivé par l’auteur</span>Lecture en ligne uniquement, avec filigrane nominatif.</span></div>
        @endif
      </div>
    </aside>
  </div>

  <section class="mt-16">
    <div class="flex flex-wrap items-end justify-between gap-3"><h2 class="font-serif text-2xl font-semibold">Mémoires similaires<span class="mt-2 block h-[2px] w-10 rounded-full bg-gold"></span></h2></div>
    <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">@foreach($similar as $s)<x-memoir-card :memoir="$s" />@endforeach</div>
  </section>
</div>
@endsection
