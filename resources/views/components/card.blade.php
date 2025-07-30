@props(['mainClass' => null, 'label' => null, 'subLabel' => null, 'bg' => null, 'actionHead' => null])
<div class="p-6 border border-zinc-100  dark:border-zinc-700 mt-2 rounded-lg shadow-md {{ $bg ? 'bg-' . $bg : 'bg-zinc-100' }} dark:bg-zinc-900 {{ $mainClass }}">
    @isset($label)
        <flux:heading size="xl" level="1">{{ $label }}</flux:heading>
    @endisset
    @isset($subLabel)
        <flux:subheading class="mb-4">{{ $subLabel }}</flux:subheading>
    @endisset
    @isset($actionHead)
        {{ $actionHead }}
    @endisset
    {{ $slot }}
</div>
