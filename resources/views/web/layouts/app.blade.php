@props([
    'title' => null,
    'scripts' => null,
    'modals' => null,
])
<x-layouts::app.sidebar :title="$title ?? null" :scripts="$scripts ?? null" :modals="$modals ?? null">
    {{ $slot }}
    @if(isset($scripts))
        <x-slot:scripts>{{ $scripts }}</x-slot:scripts>
    @endif
    @if(isset($modals))
        <x-slot:modals>{{ $modals }}</x-slot:modals>
    @endif
</x-layouts::app.sidebar>

