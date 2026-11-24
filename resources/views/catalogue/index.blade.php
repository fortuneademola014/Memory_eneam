@extends('layouts.shell')
@section('title','Catalogue – MémoBiblio')
@section('content')
<div class="mx-auto max-w-[1360px] px-4 py-6 md:px-8 md:py-8" x-data="{filtersOpen: {{ request()->hasAny(['filieres','years','supervisor','keyword']) ? 'true':'true' }}, view:'grid'}">
  <header class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
    <div>
      <h1 class="font-serif text-[32px] font-semibold leading-tight">Catalogue des mémoires</h1>
      <p class="tabular mt-1 text-sm text-muted">{{ $memoirs->total() }} mémoires disponibles</p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
      <button @click="filtersOpen=!filtersOpen" class="inline-flex h-10 items-center gap-2 rounded-[8px] border px-4 text-sm font-medium" :class="filtersOpen?'border-navy/30 bg-navy-soft/60 text-navy':'border-line bg-surface hover:bg-navy-soft/50'"><svg class="size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/></svg><span x-text="filtersOpen?'Masquer les filtres':'Filtres'"></span></button>
      <form method="GET" action="{{ route('catalogue') }}" class="flex items-center gap-2">
        @foreach((array)request('filieres',[]) as $v)<input type="hidden" name="filieres[]" value="{{ $v }}">@endforeach
        @foreach((array)request('years',[]) as $v)<input type="hidden" name="years[]" value="{{ $v }}">@endforeach
        <select name="sort" onchange="this.form.submit()" class="h-10 rounded-[8px] border border-line bg-surface px-3 pr-8 text-sm focus:border-gold focus:outline-none">
          <option value="pertinence" @selected($sort==='pertinence')>Trier : Pertinence</option>
          <option value="recents" @selected($sort==='recents')>Trier : Plus récents</option>
          <option value="lus" @selected($sort==='lus')>Trier : Plus lus</option>
        </select>
      </form>
      <div class="flex rounded-[8px] border border-line bg-surface p-0.5"><button @click="view='grid'" :class="view==='grid'?'bg-navy text-white':'text-muted'" class="flex size-9 items-center justify-center rounded-[6px]"><svg class="size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg></button><button @click="view='list'" :class="view==='list'?'bg-navy text-white':'text-muted'" class="flex size-9 items-center justify-center rounded-[6px]"><svg class="size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg></button></div>
    </div>
  </header>

  <div class="mt-8 grid gap-8" :class="filtersOpen?'lg:grid-cols-[280px_1fr]':''">
    <aside x-show="filtersOpen" class="self-start rounded-[12px] border border-line bg-surface lg:sticky lg:top-6">
      <form method="GET" action="{{ route('catalogue') }}">
        <input type="hidden" name="sort" value="{{ $sort }}">
        <div class="flex items-center justify-between px-5 py-4"><h2 class="text-sm font-semibold">Filtres</h2></div>
        <div class="border-t border-line px-4 py-4"><p class="mb-2 text-[13px] font-semibold">Filière</p><div class="flex flex-col gap-1">@foreach($filieres as $f)<label class="flex items-center gap-2.5 px-1 py-1 text-sm"><input type="checkbox" name="filieres[]" value="{{ $f->id }}" @checked(in_array($f->id,(array)request('filieres',[]))) class="size-4 rounded accent-navy"><span class="flex-1">{{ $f->name }}</span><span class="tabular text-[12px] text-muted">{{ $f->memoirs_count }}</span></label>@endforeach</div></div>
        <div class="border-t border-line px-4 py-4"><p class="mb-2 text-[13px] font-semibold">Année académique</p><div class="flex flex-col gap-1">@foreach($years as $y)<label class="flex items-center gap-2.5 px-1 py-1 text-sm"><input type="checkbox" name="years[]" value="{{ $y->label }}" @checked(in_array($y->label,(array)request('years',[]))) class="size-4 rounded accent-navy"><span class="tabular flex-1">{{ $y->label }}</span><span class="tabular text-[12px] text-muted">{{ $y->memoirs_count }}</span></label>@endforeach</div></div>
        <div class="border-t border-line px-4 py-4"><p class="mb-2 text-[13px] font-semibold">Encadreur</p><select name="supervisor" class="h-9 w-full rounded-[8px] border border-line px-3 text-sm"><option value="">Tous</option>@foreach($supervisors as $s)<option value="{{ $s->id }}" @selected(request('supervisor')==$s->id)>{{ $s->name }}</option>@endforeach</select></div>
        <div class="flex gap-2 border-t border-line p-4"><a href="{{ route('catalogue') }}" class="inline-flex h-10 flex-1 items-center justify-center rounded-[8px] border border-line text-sm font-medium hover:bg-paper">Réinitialiser</a><button class="inline-flex h-10 flex-1 items-center justify-center rounded-[8px] bg-navy text-sm font-medium text-white hover:bg-navy-deep">Appliquer</button></div>
      </form>
    </aside>

    <div class="min-w-0">
      @if($memoirs->isEmpty())
        <div class="rounded-[12px] border border-dashed border-line bg-surface">
          <x-empty-state title="Aucun mémoire ne correspond à vos critères" text="Essayez d’élargir l’année académique ou de retirer un mot-clé." action="Réinitialiser" :href="route('catalogue')" />
        </div>
      @else
        <div x-show="view==='grid'" class="grid gap-6 sm:grid-cols-2" :class="filtersOpen?'xl:grid-cols-3':'lg:grid-cols-3 xl:grid-cols-4'">
          @foreach($memoirs as $m)<x-memoir-card :memoir="$m" :favorite="in_array($m->id,$favIds)" />@endforeach
        </div>
        <div x-show="view==='list'" x-cloak class="overflow-hidden rounded-[12px] border border-line bg-surface divide-y divide-line-soft">
          @foreach($memoirs as $m)
          <a href="{{ route('memoires.show',$m) }}" class="group flex w-full items-center gap-4 px-4 py-4 text-left hover:bg-paper md:px-5">
            <div class="flex h-16 w-12 shrink-0 items-center justify-center rounded-[6px] text-white text-sm font-serif font-semibold" style="background-image:linear-gradient(145deg,#1F4E79 0%,#1F4E79 45%,#6A5F3A 80%,#B9770E 100%)">{{ $m->initials() }}</div>
            <div class="min-w-0 flex-1"><p class="line-clamp-1 font-serif text-[16px] font-semibold group-hover:text-navy">{{ $m->title }}</p><p class="mt-1 text-[13px] text-muted">{{ $m->author->name }} · {{ $m->filiere->name }} • {{ $m->academicYear->label }}</p></div>
            <span class="tabular hidden text-[13px] text-muted sm:block">{{ number_format($m->reads_count,0,',',' ') }} lectures</span>
          </a>
          @endforeach
        </div>
        <div class="mt-10">{{ $memoirs->links() }}</div>
      @endif
    </div>
  </div>
</div>
@endsection
