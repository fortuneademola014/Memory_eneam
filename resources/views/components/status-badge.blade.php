@props(['status'])
@php
  $map = [
    'soumis'=>['Soumis','#1D4ED8','#2563EB14'],
    'verification'=>['En vérification','#B45309','#D977061A'],
    'valide'=>['Validé','#15803D','#16A34A17'],
    'publie'=>['Publié','#15803D','#16A34A17'],
    'rejete'=>['Rejeté','#B91C1C','#DC262614'],
    'embargo'=>['Embargo','#6D28D9','#7C3AED14'],
    'restreint'=>['Accès restreint','#6D28D9','#7C3AED14'],
  ];
  [$label,$fg,$bg] = $map[$status] ?? [ucfirst($status),'#4B5563','#6B72801A'];
@endphp
<span {{ $attributes->merge(['class'=>'inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-medium']) }} style="color:{{ $fg }};background:{{ $bg }}">
  <span class="size-1.5 rounded-full" style="background:{{ $fg }}"></span>{{ $label }}
</span>
