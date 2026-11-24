@extends('layouts.app')
@section('title','Connexion – MémoBiblio')
@section('body')
<div class="flex min-h-screen items-center justify-center bg-paper px-4 py-10">
  <div class="w-full max-w-[440px] rounded-[12px] border border-line bg-surface p-8 shadow-lift">
    <a href="{{ route('landing') }}" class="flex items-center gap-2.5"><span class="flex size-8 items-center justify-center rounded-[8px] bg-navy font-serif font-bold text-white">M</span><span class="flex flex-col leading-none"><span class="font-serif text-[17px] font-semibold">MémoBiblio</span><span class="mt-1 text-[10px] font-semibold tracking-[0.14em] text-gold">ENEAM</span></span></a>
    <h1 class="mt-10 font-serif text-3xl font-semibold">Bon retour parmi nous</h1>
    <p class="mt-2 text-sm text-muted">Connectez-vous avec votre matricule ou votre e-mail institutionnel.</p>
    @if($errors->any())<div role="alert" class="mt-6 rounded-[8px] border border-st-rejected/20 bg-st-rejected/[0.06] p-3 text-[13px] text-[#B91C1C]">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('login.store') }}" class="mt-6 flex flex-col gap-5 {{ $errors->any()?'animate-shake':'' }}">@csrf
      <div class="flex flex-col gap-1.5"><label class="text-sm font-medium">Matricule ou e-mail</label><input name="login" value="{{ old('login') }}" class="h-10 w-full rounded-[8px] border {{ $errors->has('login')?'border-st-rejected':'border-line' }} px-3 text-sm focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40"></div>
      <div class="flex flex-col gap-1.5"><label class="text-sm font-medium">Mot de passe</label><input name="password" type="password" class="h-10 w-full rounded-[8px] border {{ $errors->any()?'border-st-rejected':'border-line' }} px-3 text-sm focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40"></div>
      <div class="flex items-center justify-between text-[13px]"><label class="flex items-center gap-2"><input type="checkbox" name="remember" class="size-4 rounded accent-navy" checked>Rester connecté</label><span class="text-navy font-medium">Mot de passe oublié ?</span></div>
      <button class="h-12 rounded-[8px] bg-navy text-[15px] font-medium text-white hover:bg-navy-deep">Se connecter</button>
    </form>
    <p class="mt-6 text-center text-sm text-muted">Pas encore de compte ? <a href="{{ route('register') }}" class="font-medium text-navy hover:underline">Créer un compte</a></p>
  </div>
</div>
@endsection
