@extends('layouts.shell')
@section('title','Mes dépôts – MémoBiblio')
@section('content')
<div class="mx-auto max-w-[1200px] px-4 py-6 md:px-8 md:py-8" x-data="{tab:'tous'}">
  <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div><h1 class="font-serif text-[32px] font-semibold">Mes dépôts</h1><p class="mt-1 text-sm text-muted">Suivez l’avancement de vos mémoires déposés.</p></div>
    <a href="{{ route('deposits.create') }}" class="inline-flex h-10 items-center gap-2 rounded-[8px] bg-gold px-4 text-sm font-medium text-white hover:bg-gold-deep"><svg class="size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>Nouveau dépôt</a>
  </div>
  @php $tabs=['tous'=>'Tous','en-cours'=>'En cours','valides'=>'Validés','rejetes'=>'Rejetés']; $counts=['tous'=>$memoirs->count(),'en-cours'=>$memoirs->whereIn('status',['soumis','verification'])->count(),'valides'=>$memoirs->whereIn('status',['valide','publie'])->count(),'rejetes'=>$memoirs->where('status','rejete')->count()]; @endphp
  <div class="mt-6 flex gap-1 overflow-x-auto border-b border-line">
    @foreach($tabs as $k=>$label)
      <button @click="tab='{{ $k }}'" :class="tab==='{{ $k }}'?'border-gold font-semibold text-ink':'border-transparent text-muted hover:text-ink'" class="-mb-px flex items-center gap-2 border-b-2 px-3 py-2.5 text-sm">{{ $label }} <span class="tabular rounded-full px-1.5 py-0.5 text-[11px] font-semibold" :class="tab==='{{ $k }}'?'bg-navy text-white':'bg-line-soft text-muted'">{{ $counts[$k] }}</span></button>
    @endforeach
  </div>

  <div class="mt-6 space-y-4">
    @forelse($memoirs as $m)
      @php $group = in_array($m->status,['soumis','verification'])?'en-cours':(in_array($m->status,['valide','publie'])?'valides':'rejetes'); @endphp
      <article x-show="tab==='tous' || tab==='{{ $group }}'" class="rounded-[12px] border border-line bg-surface p-5 shadow-rest">
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
          <div class="flex min-w-0 gap-4">
            <div class="flex h-20 w-14 shrink-0 items-center justify-center rounded-[6px] text-white font-serif font-semibold" style="background-image:linear-gradient(145deg,#1F4E79 0%,#1F4E79 45%,#6A5F3A 80%,#B9770E 100%)">{{ $m->initials() }}</div>
            <div class="min-w-0">
              <h3 class="font-serif text-lg font-semibold leading-snug">{{ $m->title }}</h3>
              <p class="mt-1 text-[13px] text-muted">Soumis le {{ optional($m->submitted_at)->translatedFormat('d F Y') }} · {{ $m->filiere->name }} • {{ $m->academicYear->label }}</p>
              <div class="mt-3"><x-status-badge :status="$m->status" /></div>
            </div>
          </div>
          <a href="{{ route('memoires.show',$m) }}" class="inline-flex h-9 items-center justify-center rounded-[8px] border border-line px-4 text-sm font-medium hover:bg-paper">Voir</a>
        </div>
        <div class="mt-5">
          @php $steps=['Soumis','En vérification',$m->status==='rejete'?'Rejeté':'Validé','Publié']; $reached=['soumis'=>0,'verification'=>1,'rejete'=>2,'valide'=>2,'publie'=>3][$m->status] ?? 0; @endphp
          <ol class="flex w-full items-start">
            @foreach($steps as $i=>$label)
              @php $done=$i<$reached || ($i===$reached && $m->status==='publie'); $current=$i===$reached && $m->status!=='publie'; $isReject=$m->status==='rejete' && $i===2; @endphp
              <li class="relative flex flex-1 flex-col items-center gap-2 text-center last:flex-none sm:last:flex-1">
                @if($i < count($steps)-1)<span class="absolute left-1/2 top-3 h-[2px] w-full {{ $i < $reached ? 'bg-navy':'bg-line' }}"></span>@endif
                <span class="relative z-10 flex size-6 items-center justify-center rounded-full border-2 text-[11px] font-semibold {{ $done?'border-navy bg-navy text-white':($isReject?'border-st-rejected bg-st-rejected text-white':($current?'border-navy bg-surface text-navy':'border-line bg-surface text-muted')) }}">{{ $done ? '✓' : ($isReject?'×':$i+1) }}</span>
                <span class="px-1 text-[12px] {{ $done||$current?'font-medium text-ink':'text-muted' }} {{ $isReject?'text-st-rejected':'' }}">{{ $label }}</span>
              </li>
            @endforeach
          </ol>
        </div>
        @if($m->status==='rejete')
        <div class="mt-4 rounded-[8px] bg-st-rejected/[0.06] p-4 text-sm text-[#991B1B]">
          <p class="font-semibold">Motif du rejet</p>
          <p class="mt-1">{{ $m->rejection_reason }}</p>
          <a href="{{ route('deposits.create') }}" class="mt-3 inline-flex h-9 items-center rounded-[8px] bg-navy px-4 text-sm font-medium text-white hover:bg-navy-deep">Corriger et redéposer</a>
        </div>
        @endif
      </article>
    @empty
      <div class="rounded-[12px] border border-dashed border-line bg-surface"><x-empty-state title="Vous n’avez pas encore déposé de mémoire" text="Commencez par soumettre votre premier mémoire pour le faire valider par la bibliothèque." action="+ Nouveau dépôt" :href="route('deposits.create')" /></div>
    @endforelse
  </div>
</div>
@endsection
