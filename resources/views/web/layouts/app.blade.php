@props([
    'title' => null,
    'scripts' => null,
    'modals' => null,
])
<x-layouts::app.sidebar :title="$title ?? null">
    {{ $slot }}
    @isset($scripts)
        <x-slot:scripts>{{ $scripts }}</x-slot:scripts>
    @endisset
    @isset($modals)
        <x-slot:modals>{{ $modals }}</x-slot:modals>
    @endisset
</x-layouts::app.sidebar>

