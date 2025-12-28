@props(['badge', 'class' => ''])
<span {{ $attributes->merge(['class' => 'badge ' . $class]) }}>
    {{ $badge }}
</span>
