@props(['label','value','delta'=>null,'trend'=>'up'])
<div {{ $attributes->merge(['class'=>'flex min-w-0 flex-col gap-3 rounded-[12px] border border-line bg-surface p-5 shadow-rest']) }}>
  <div class="text-[13px] font-medium text-muted">{{ $label }}</div>
  <div class="tabular text-[34px] font-semibold leading-none tracking-tight text-ink">{{ $value }}</div>
  @if($delta)
  <div class="flex items-center gap-1 text-xs text-muted"><span class="{{ $trend==='up'?'text-st-valid':'text-st-rejected' }} font-semibold">{{ $delta }}</span> vs mois précédent</div>
  @endif
</div>
