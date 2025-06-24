<?php

use Livewire\Volt\Component;

new class extends Component {

    public function mount(): void
    {
        if(session('status')) {
            $this->success(session('status'));
        }
    }
}; ?>

<section class="mt-2">
    <x-partials.sidebar menu="setting" active="Pengaturan / Penampilan">
        <x-card :label="__('Appearance')" :sub-label=" __('Update the appearance pages for your account')">
            <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
                <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
                <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
                <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
            </flux:radio.group>
        </x-card>
    </x-partials.sidebar>
</section>
