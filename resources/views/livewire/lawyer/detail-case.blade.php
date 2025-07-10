<?php

use App\Models\LegalCase;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\{ Computed, Url, Title };

new class extends Component {
    use WithFileUploads;
    use WithPagination;

    public ?LegalCase $case;
    public $queryStatus;

    public function mount(LegalCase $case, $status): void
    {
        $this->case = $case;
        $this->queryStatus = $status;
    }

}; ?>

<x-partials.sidebar :id-detail="$this->case?->id" :back="route('lawyer.case')" menu="lawyer-active-case"
                    active="Penanganan Kasus / {{ Str::ucfirst($this->case?->title) }} / Detail Kasus">
    <x-slot:profile>
        <div class="flex flex-col border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 flex-shrink-0">
            <div
                class="flex flex-col items-center border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 w-full py-6 px-4 rounded-lg">
                <flux:heading size="xl" class="text-center text-xl mb-2">KLIEN</flux:heading>
                <div class="h-20 w-20 rounded-full border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                    <flux:avatar size="xl" class="size-full " :name="$this->case->client->user->name"
                                 :initials="$this->case->client->user->initials()"/>
                </div>
                <div class="text-sm font-semibold mt-2">{{ $this->case->client->user->name }}</div>
                <div class="text-xs text-gray-500">{{ $this->case->client->user->email }}</div>
            </div>
        </div>
    </x-slot:profile>
    <livewire:detail-staff-case :id="$this->case?->id" :status="$this->queryStatus"/>
</x-partials.sidebar>
