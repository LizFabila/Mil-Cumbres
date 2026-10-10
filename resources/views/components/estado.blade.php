@props(['valor'])
<span {{ $attributes->class(['estado', "estado--{$valor}"]) }}>{{ str_replace('_', ' ', $valor) }}</span>
