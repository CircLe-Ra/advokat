<flux:navbar class="-mb-px max-lg:hidden ">
    <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
        @role('klien')
            <flux:tooltip content="Hubungi Petugas" position="bottom">
                <flux:button class="{{ request()->routeIs('client.chat') ? '!text-accent' : '' }}" href="{{ route('client.chat') }}" icon="chat-bubble-left-right" variant="subtle" aria-label="Tombol obrolan" wire:navigate />
            </flux:tooltip>
        @endrole
        @role('staf')
            <flux:tooltip content="Hubungi Klien" position="bottom">
                <flux:button class="{{ request()->routeIs('staff.chat') ? '!text-accent' : '' }}" href="{{ route('staff.chat') }}" icon="chat-bubble-left-right" variant="subtle" aria-label="Tombol obrolan" wire:navigate />
            </flux:tooltip>
        @endrole
        <flux:tooltip :content="__('Appearance')" position="bottom">
            <flux:button x-data x-on:click="$flux.dark = ! $flux.dark" icon="moon" variant="subtle" aria-label="Toggle dark mode" />
        </flux:tooltip>
    </flux:navbar>
</flux:navbar>

