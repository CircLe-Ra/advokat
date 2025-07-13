<?php

use App\Models\MeetingSchedule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\{Computed, On, Url, Title};
use Carbon\Carbon;

new class extends Component {

    public ?int $id = null;
    public ?string $notes = null;
    public bool $file_collection = false;
    public $file_submission_deadline;

    public $meeting;

    public function mount($id): void
    {
        $meeting = MeetingSchedule::find($id);
        $this->meeting = $meeting;
        $this->notes = $meeting->notes;
        $this->file_collection = $meeting->file_collection == 'yes';
        $this->file_submission_deadline = $meeting->file_submission_deadline;
    }

}; ?>

<x-partials.sidebar :id-detail="$this->meeting->legalCase?->id" menu="lawyer-active-case"
                    active="Penanganan Kasus / Jadwal Pertemuan / {{ $this->meeting->legalCase?->title }} / Hasil Pertemuan">
    <x-slot:profile>
        <div class="flex flex-col border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 flex-shrink-0">
            <div
                class="flex flex-col items-center border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 w-full py-6 px-4 rounded-lg">
                <flux:heading size="xl" class="text-center text-xl mb-2">KLIEN</flux:heading>
                <div class="h-20 w-20 rounded-full border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                    <flux:avatar size="xl" class="size-full " :name="$this->meeting->legalCase?->client->user->name"
                                 :initials="$this->meeting->legalCase?->client->user->initials()"/>
                </div>
                <div class="text-sm font-semibold mt-2">{{ $this->meeting->legalCase?->client->user->name }}</div>
                <div class="text-xs text-gray-500">{{ $this->meeting->legalCase?->client->user->email }}</div>
            </div>
        </div>
    </x-slot:profile>
    @if($this->meeting->status == 'cancelled')
        <flux:callout icon="no-symbol" variant="danger" inline class="mt-2 mb-1">
            <flux:callout.heading>Pertemuan dibatalkan
                pada {{ $this->meeting->updated_at->isoFormat('dddd, D MMMM YYYY HH:mm') }} WIT
            </flux:callout.heading>
            <x-slot name="actions">
                <flux:button icon:trailing="arrow-right" variant="outline"
                             href="{{ route('staff.active.page', ['id' => $this->meeting->legal_case_id, 'status' => 'schedule']) }}">
                    Kembali
                </flux:button>
            </x-slot>
        </flux:callout>
    @endif
    <x-card label="Hasil Pertemuan" sub-label="Hasil pertemuan klien dengan pengacara.">
        <div class="flex gap-2 justify-between">
            <div>
                <div class="w-full min-w-xs">
                    <div class="p-6 border border-zinc-200 dark:border-zinc-700 mt-1 rounded-lg bg-white dark:bg-zinc-900">
                        <flux:heading size="xl" level="1">Pemberkasan</flux:heading>
                        <flux:text class="text-zinc-600 dark:text-zinc-400 mt-2 mb-6">
                            Opsi penambahan berkas yang dibutuhkan.
                        </flux:text>
                        <flux:fieldset>
                            <div class="space-y-4">
                                <flux:switch disabled wire:model="file_collection" label="Penambahan Berkas?" />
                            </div>
                            @if($this->file_collection)
                            <flux:separator  class="my-4" />
                            <div wire:show="file_collection" wire:cloak wire:transition.scale.origin.top>
                                <flux:input disabled type="date" wire:model="file_submission_deadline" label="Tenggat Waktu Pengumpulan Berkas"/>
                            </div>
                            @endif
                        </flux:fieldset>
                    </div>
                </div>
            </div>
            <div class="w-full">
            <div class="p-6 border border-zinc-200 dark:border-zinc-700 mt-1 rounded-lg bg-white dark:bg-zinc-900">
                <flux:heading size="xl" level="1">Catatan</flux:heading>
                <flux:subheading class="mb-4">Ringkasan/Rangkuman pertemuan klien dengan pengacara.</flux:subheading>
                @if($this->notes)
                    <div class="border rounded-lg border-zinc-300 dark:border-zinc-600 p-2">
                        {!! $this->notes !!}
                    </div>
                @else
                    <div class="border rounded-lg border-zinc-300 dark:border-zinc-600 px-2 py-20 text-center">
                        <flux:text class="text-zinc-600 dark:text-zinc-400">
                            Belum ada catatan.
                        </flux:text>
                    </div>
                @endif
            </div>
        </div>
        </div>
{{--        <x-card main-class="" bg="white">--}}

{{--        </x-card>--}}
    </x-card>
</x-partials.sidebar>
