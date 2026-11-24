<!doctype html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'MémoBiblio – ENEAM')</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-paper text-ink">
  @yield('body')
  @if(session('toast'))
    <div x-data="{show:true}" x-init="setTimeout(()=>show=false,4000)" x-show="show" x-transition class="fixed right-4 top-20 z-[60] w-[340px] max-w-[calc(100vw-2rem)] rounded-[12px] border border-line bg-surface p-4 shadow-pop">
      <div class="flex items-start gap-3">
        <span class="mt-0.5 text-st-valid"><svg class="size-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg></span>
        <div class="min-w-0 flex-1"><p class="text-sm font-semibold">{{ session('toast') }}</p></div>
        <button @click="show=false" class="text-muted hover:text-ink"><svg class="size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg></button>
      </div>
    </div>
  @endif
</body>
</html>
