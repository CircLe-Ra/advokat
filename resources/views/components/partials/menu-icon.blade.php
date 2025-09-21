<flux:navbar class="-mb-px max-lg:hidden ">
    <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
        <livewire:notification />
        <flux:tooltip :content="__('Appearance')" position="bottom">
            <flux:button size="sm" class="!text-white"
                         x-data
                         x-on:click="$flux.dark = ! $flux.dark"
                         icon="moon"
                         variant="subtle"
                         aria-label="Toggle dark mode" />
        </flux:tooltip>
    </flux:navbar>
</flux:navbar>
