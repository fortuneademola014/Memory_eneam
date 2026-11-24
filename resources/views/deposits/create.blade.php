@extends('layouts.shell')
@section('title','Nouveau dépôt – MémoBiblio')
@section('content')
<div class="mx-auto max-w-[980px] px-4 py-6 md:px-8 md:py-8" x-data="{step:1, title:'{{ old('title') }}', abstract:'{{ e(old('abstract')) }}', keywords:'{{ old('keywords','digitalisation, PME') }}', fileName:''}">
  <h1 class="font-serif text-[32px] font-semibold">Nouveau dépôt</h1>
  <p class="mt-1 text-sm text-muted">Soumettez votre mémoire pour validation par la bibliothèque.</p>

  <div class="mt-8 flex items-center gap-3">
    @foreach([1=>'Informations',2=>'Fichier PDF',3=>'Récapitulatif'] as $n=>$label)
      <div class="flex items-center gap-2"><span class="flex size-8 items-center justify-center rounded-full text-sm font-semibold" :class="step>{{ $n }} ? 'bg-navy text-white' : (step==={{ $n }} ? 'bg-gold text-white' : 'bg-line text-muted')">{{ $n }}</span><span class="text-sm" :class="step==={{ $n }}?'font-semibold text-ink':'text-muted'">{{ $label }}</span></div>
      @if($n<3)<div class="h-px flex-1 bg-line"></div>@endif
    @endforeach
  </div>

  @if($errors->any())<div class="mt-6 rounded-[8px] border border-st-rejected/20 bg-st-rejected/[0.06] p-4 text-sm text-[#B91C1C]"><ul class="list-disc pl-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

  <form method="POST" action="{{ route('deposits.store') }}" enctype="multipart/form-data" class="mt-6 rounded-[12px] border border-line bg-surface p-6 shadow-rest md:p-8">
    @csrf
    <div x-show="step===1" class="space-y-5">
      <div class="flex flex-col gap-1.5"><label class="text-sm font-medium">Titre du mémoire</label><input name="title" x-model="title" required class="h-10 rounded-[8px] border border-line px-3 text-sm focus:border-gold focus:outline-none" placeholder="Titre complet du mémoire"><p class="text-[13px] text-gold-deep">Vérifiez qu’aucun mémoire similaire n’existe déjà dans le catalogue.</p></div>
      <div class="flex flex-col gap-1.5"><label class="text-sm font-medium">Résumé</label><textarea name="abstract" x-model="abstract" rows="6" required class="rounded-[8px] border border-line px-3 py-2 text-sm focus:border-gold focus:outline-none" placeholder="Décrivez le contexte, la méthode et les principaux résultats…"></textarea><p class="text-[12px] text-muted"><span x-text="abstract.length"></span> caractères</p></div>
      <div class="flex flex-col gap-1.5"><label class="text-sm font-medium">Mots-clés (séparés par des virgules)</label><input name="keywords" x-model="keywords" class="h-10 rounded-[8px] border border-line px-3 text-sm focus:border-gold focus:outline-none" placeholder="digitalisation, PME, performance"></div>
      <div class="grid gap-5 sm:grid-cols-2">
        <div class="flex flex-col gap-1.5"><label class="text-sm font-medium">Filière</label><select name="filiere_id" class="h-10 rounded-[8px] border border-line px-3 text-sm focus:border-gold focus:outline-none">@foreach($filieres as $f)<option value="{{ $f->id }}" @selected(old('filiere_id')==$f->id)>{{ $f->name }}</option>@endforeach</select></div>
        <div class="flex flex-col gap-1.5"><label class="text-sm font-medium">Année académique</label><select name="academic_year_id" class="h-10 rounded-[8px] border border-line px-3 text-sm focus:border-gold focus:outline-none">@foreach($years as $y)<option value="{{ $y->id }}" @selected(old('academic_year_id')==$y->id)>{{ $y->label }}</option>@endforeach</select></div>
      </div>
      <div class="flex flex-col gap-1.5"><label class="text-sm font-medium">Encadreur</label><select name="supervisor_id" class="h-10 rounded-[8px] border border-line px-3 text-sm focus:border-gold focus:outline-none">@foreach($supervisors as $s)<option value="{{ $s->id }}" @selected(old('supervisor_id')==$s->id)>{{ $s->name }}</option>@endforeach</select></div>
    </div>

    <div x-show="step===2" x-cloak class="space-y-5">
      <div class="rounded-[12px] border-2 border-dashed border-gold/50 bg-gold-soft/20 p-10 text-center">
        <svg class="mx-auto size-10 text-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12-3-3m0 0-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
        <p class="mt-4 font-medium">Glissez votre PDF ici ou parcourez</p>
        <p class="mt-1 text-sm text-muted">Format PDF uniquement · 30 Mo maximum</p>
        <input type="file" name="pdf" accept="application/pdf" @change="fileName=$event.target.files[0]?.name||''" class="mt-4 block w-full text-sm text-muted file:mr-4 file:rounded-[8px] file:border-0 file:bg-navy file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-navy-deep">
        <p x-show="fileName" class="mt-3 text-sm text-navy font-medium" x-text="fileName"></p>
      </div>
    </div>

    <div x-show="step===3" x-cloak class="space-y-5">
      <h2 class="font-serif text-xl font-semibold">Récapitulatif</h2>
      <dl class="grid gap-x-8 border-t border-line sm:grid-cols-2">
        <div class="border-b border-line-soft py-3 text-sm"><dt class="text-muted">Titre</dt><dd class="mt-1 font-medium" x-text="title"></dd></div>
        <div class="border-b border-line-soft py-3 text-sm"><dt class="text-muted">Mots-clés</dt><dd class="mt-1 font-medium" x-text="keywords"></dd></div>
        <div class="border-b border-line-soft py-3 text-sm sm:col-span-2"><dt class="text-muted">Résumé</dt><dd class="mt-1 font-medium" x-text="abstract"></dd></div>
        <div class="border-b border-line-soft py-3 text-sm"><dt class="text-muted">Fichier</dt><dd class="mt-1 font-medium" x-text="fileName || 'Aucun fichier sélectionné (optionnel en démo)'"></dd></div>
      </dl>
      <label class="flex items-start gap-3 text-sm"><input type="checkbox" name="certify" value="1" required class="mt-1 size-4 rounded accent-navy"><span>Je certifie que ce mémoire est original et que j’ai le droit de le déposer sur MémoBiblio.</span></label>
    </div>

    <div class="mt-8 flex items-center justify-between border-t border-line pt-5">
      <button type="button" @click="step=Math.max(1,step-1)" class="inline-flex h-10 items-center rounded-[8px] border border-line px-4 text-sm font-medium hover:bg-paper" :class="step===1?'invisible':''">Précédent</button>
      <div>
        <button type="button" x-show="step<3" @click="step++" class="inline-flex h-10 items-center rounded-[8px] bg-navy px-5 text-sm font-medium text-white hover:bg-navy-deep">Suivant</button>
        <button type="submit" x-show="step===3" x-cloak class="inline-flex h-10 items-center rounded-[8px] bg-gold px-5 text-sm font-medium text-white hover:bg-gold-deep">Soumettre le mémoire</button>
      </div>
    </div>
  </form>
</div>
@endsection
