<?php

use App\Models\LegalCase;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\{ Computed, Url, Title };

new class extends Component {
    use WithFileUploads;
    use WithPagination;

    public $case;
    public $queryStatus;

    public function mount($id): void
    {
        $case = LegalCase::find($id);
        $this->case = $case;
    }

}; ?>

<x-partials.sidebar position="right" :id-detail="$this->case?->id" :back="route('client.case')" menu="client-active-case"
                    active="Penanganan Kasus / {{ Str::ucfirst($this->case?->title) }} / Detail Kasus">
    <x-slot:profile>
        <div class="flex flex-col  dark:border-zinc-700 dark:bg-zinc-800 flex-shrink-0">
            <div
                class="flex flex-col items-center border border-zinc-100 bg-zinc-100 shadow-md dark:border-zinc-700 dark:bg-zinc-900 w-full py-6 px-4 rounded-lg">
                <flux:heading size="xl" class="text-center text-xl mb-2">PENGACARA</flux:heading>
                <div class="h-20 w-20 rounded-full border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                    <flux:avatar size="xl" class="size-full " :name="$this->case->lawyer->user->name"
                                 :initials="$this->case->lawyer->user->initials()"/>
                </div>
                <div class="text-sm font-semibold mt-2">{{ $this->case->lawyer->user->name }}</div>
                <div class="text-xs text-gray-500">{{ $this->case->lawyer->user->email }}</div>
            </div>
        </div>
    </x-slot:profile>
    <livewire:detail-case :id="$this->case?->id"/>
</x-partials.sidebar>
