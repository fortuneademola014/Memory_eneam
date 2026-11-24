@props(['memoir','favorite'=>false])
<article class="group relative flex h-full flex-col overflow-hidden rounded-[12px] border border-line bg-surface shadow-rest transition-[transform,box-shadow] duration-200 hover:-translate-y-0.5 hover:shadow-lift">
  <div class="relative flex h-36 shrink-0 items-center justify-center overflow-hidden bg-navy text-white" style="background-image:linear-gradient(145deg,#1F4E79 0%,#1F4E79 45%,#6A5F3A 80%,#B9770E 100%)" aria-hidden="true">
    <span class="font-serif text-4xl font-semibold tracking-wide text-white/95">{{ $memoir->initials() }}</span>
    <span class="absolute left-4 top-4 h-px w-8 bg-white/40"></span>
    <span class="absolute right-3 top-3 flex items-center gap-1 rounded-[6px] bg-white/15 px-1.5 py-0.5 text-[10px] font-semibold tracking-wide text-white">PDF</span>
  </div>
  <div class="flex flex-1 flex-col gap-3 p-4">
    <h3 class="font-serif text-[17px] font-semibold leading-snug text-ink">
      <a href="{{ route('memoires.show',$memoir) }}" class="line-clamp-2 text-left after:absolute after:inset-0">
        <span class="bg-[linear-gradient(#B9770E,#B9770E)] bg-[length:0%_1.5px] bg-left-bottom bg-no-repeat transition-[background-size] duration-200 group-hover:bg-[length:100%_1.5px]">{{ $memoir->title }}</span>
      </a>
    </h3>
    <div class="flex items-center gap-2 text-[13px]">
      <span class="inline-flex size-[22px] shrink-0 items-center justify-center rounded-full bg-navy-soft text-[9px] font-semibold text-navy">{{ $memoir->author->initials() }}</span>
      <span class="truncate font-medium text-ink/85">{{ $memoir->author->name }}</span>
    </div>
    <p class="text-[13px] text-muted">{{ $memoir->filiere->name }} • {{ $memoir->academicYear->label }}</p>
    <div class="flex flex-wrap gap-1.5">
      @foreach($memoir->keywords->take(3) as $kw)
        <span class="inline-flex rounded-full border border-line bg-paper px-2.5 py-0.5 text-xs text-ink/75">{{ $kw->keyword }}</span>
      @endforeach
    </div>
    <div class="mt-auto flex items-center justify-between border-t border-line-soft pt-3">
      <span class="tabular flex items-center gap-1.5 text-[13px] text-muted">
        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        {{ number_format($memoir->reads_count,0,',',' ') }} lectures
      </span>
      @auth
      <form method="POST" action="{{ route('memoires.favorite',$memoir) }}" class="relative z-10">@csrf
        <button aria-label="Favori" class="rounded-full p-1.5 {{ $favorite ? 'text-st-rejected':'text-muted hover:bg-navy-soft hover:text-navy' }}">
          <svg class="size-[18px]" fill="{{ $favorite ? 'currentColor':'none' }}" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
        </button>
      </form>
      @endauth
    </div>
  </div>
</article>
