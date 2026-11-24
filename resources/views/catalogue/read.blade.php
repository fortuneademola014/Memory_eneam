@extends('layouts.app')
@section('title','Lecteur – '.$memoir->title)
@section('body')
<div class="min-h-screen bg-[#F3F2EE] text-ink">
  <div class="sticky top-0 z-30 flex items-center gap-3 border-b border-line bg-surface/95 px-4 py-3 backdrop-blur">
    <a href="{{ route('memoires.show',$memoir) }}" class="rounded-full p-2 text-muted hover:bg-paper hover:text-ink" aria-label="Retour"><svg class="size-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg></a>
    <div class="min-w-0 flex-1"><p class="truncate font-serif text-sm font-semibold">{{ $memoir->title }}</p><p class="tabular text-[12px] text-muted">Page {{ $history->last_page }} / {{ $history->total_pages }}</p></div>
    <div class="hidden items-center gap-2 sm:flex"><span class="rounded-[8px] border border-line bg-paper px-3 py-1.5 text-sm tabular">100%</span></div>
    <a href="{{ route('memoires.show',$memoir) }}" class="rounded-full p-2 text-muted hover:bg-paper hover:text-ink" aria-label="Fermer"><svg class="size-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg></a>
  </div>
  @if($history->last_page > 1)
  <div class="mx-auto mt-4 flex max-w-[760px] items-center justify-between gap-3 rounded-[10px] border border-gold/30 bg-gold-soft px-4 py-3 text-sm"><span>Reprendre à la page {{ $history->last_page }}</span><a href="#page-{{ $history->last_page }}" class="rounded-[8px] bg-gold px-3 py-1.5 font-medium text-white hover:bg-gold-deep">Reprendre</a></div>
  @endif
  <div class="mx-auto max-w-[760px] px-4 py-8">
    <div class="relative overflow-hidden rounded-[4px] bg-white shadow-[0_1px_3px_rgba(15,27,45,0.08),0_12px_32px_rgba(15,27,45,0.08)]">
      <div class="px-[9%] py-[8%] font-serif text-ink" style="font-size:13px">
        <div class="flex flex-col items-center text-center">
          <p class="font-sans text-[0.75em] font-semibold tracking-[0.12em]">RÉPUBLIQUE DU BÉNIN</p>
          <p class="mt-1 font-sans text-[0.7em] text-muted">Université d’Abomey-Calavi</p>
          <p class="mt-3 text-[0.95em] font-semibold">École Nationale d’Économie Appliquée et de Management</p>
          <span class="my-[8%] h-px w-1/3 bg-[#B9770E]"></span>
          <p class="font-sans text-[0.75em] text-muted">Mémoire de fin de formation</p>
          <h1 class="mt-6 text-[1.7em] font-semibold leading-snug">{{ $memoir->title }}</h1>
          <div class="mt-auto grid w-full grid-cols-2 gap-6 pt-16 text-left text-[0.8em]"><div><p class="font-sans text-[0.85em] text-muted">Présenté par</p><p class="font-semibold">{{ $memoir->author->name }}</p></div><div class="text-right"><p class="font-sans text-[0.85em] text-muted">Sous la direction de</p><p class="font-semibold">{{ $memoir->supervisor->name ?? '—' }}</p></div></div>
          <p class="mt-6 font-sans text-[0.7em] text-muted">Année académique {{ $memoir->academicYear->label }}</p>
        </div>
        <div class="mt-16 border-t border-line pt-10 font-sans text-[0.9em] leading-[1.7] text-ink/85">
          <h2 class="font-serif text-[1.35em] font-semibold text-ink">Résumé</h2>
          <p class="mt-4 indent-6">{{ $memoir->abstract }}</p>
        </div>
        <p class="tabular mt-16 text-center font-sans text-[0.8em] text-muted">1</p>
      </div>
      <div aria-hidden="true" class="pointer-events-none absolute inset-0 flex items-center justify-center overflow-hidden"><span class="whitespace-nowrap font-sans text-[26px] font-semibold text-ink" style="opacity:.06;transform:rotate(-32deg)">MémoBiblio · {{ auth()->user()->name }} · {{ now()->format('d/m/Y') }}</span></div>
    </div>
  </div>
</div>
@endsection
