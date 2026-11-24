@props(['title','text','action'=>null,'href'=>null])
<div {{ $attributes->merge(['class'=>'flex flex-col items-center px-6 py-16 text-center']) }}>
  <svg viewBox="0 0 160 110" class="mb-6 w-40" aria-hidden="true" fill="none">
    <ellipse cx="80" cy="100" rx="58" ry="5" fill="#E7E5DF"/>
    <path d="M80 26 C62 16 38 14 18 18 V88 C38 84 62 86 80 96 Z" fill="#FFFFFF" stroke="#1F4E79" stroke-width="1.5"/>
    <path d="M80 26 C98 16 122 14 142 18 V88 C122 84 98 86 80 96 Z" fill="#FFFFFF" stroke="#1F4E79" stroke-width="1.5"/>
    <path d="M80 26 V96" stroke="#1F4E79" stroke-width="1.5"/>
    <path d="M112 18 V44 L118 39 L124 44 V16" fill="#B9770E"/>
  </svg>
  <h3 class="max-w-md font-serif text-xl font-semibold text-ink">{{ $title }}</h3>
  <p class="mt-2 max-w-sm text-sm text-muted">{{ $text }}</p>
  @if($action && $href)<div class="mt-6"><a href="{{ $href }}" class="inline-flex h-10 items-center rounded-[8px] bg-gold px-4 text-sm font-medium text-white hover:bg-gold-deep">{{ $action }}</a></div>@endif
</div>
