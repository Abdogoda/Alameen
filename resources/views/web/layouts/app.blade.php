@props([
    'title' => null,
    'description' => null,
    'course' => null,
    'robots' => null,
    'scripts' => null,
    'modals' => null,
])
<x-layouts::app.sidebar :title="$title ?? null" :description="$description ?? null" :course="$course ?? null" :robots="$robots ?? null">
    {{ $slot }}
    @isset($scripts)
        <x-slot:scripts>{{ $scripts }}</x-slot:scripts>
    @endisset
    @isset($modals)
        <x-slot:modals>{{ $modals }}</x-slot:modals>
    @endisset
</x-layouts::app.sidebar>

