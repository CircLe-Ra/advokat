<?php

use App\Models\CourtResult;
use App\Models\CourtSchedule;
use App\Models\MeetingSchedule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\{Computed, On, Url, Title};
use Carbon\Carbon;

new class extends Component {
    use WithFileUploads;

    public ?int $id = null;
    public string $reason_for_postponement = '';
    public $files = [];
    public $file;

    public $court;

    public function mount($id): void
    {
        $court = CourtSchedule::find($id);
        $this->court = $court;
    }

    #[Computed]
    public function courtResults()
    {
        return CourtResult::where('court_schedule_id', $this->court->id)->get();
    }

    public function __reset(): void
    {
        $this->reset(['reason_for_postponement', 'files']);
        $this->resetValidation(['reason_for_postponement', 'files']);
    }

}; ?>

<x-partials.sidebar :id-detail="$this->court->legalCase?->id" menu="lawyer-active-case"
                    active="Penanganan Kasus / Jadwal Sidang / {{ $this->court->legalCase?->title }} / Hasil Sidang">
    <x-slot:profile>
        <div class="flex flex-col border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 flex-shrink-0">
            <div
                class="flex flex-col items-center border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 w-full py-6 px-4 rounded-lg">
                <flux:heading size="xl" class="text-center text-xl mb-2">KLIEN</flux:heading>
                <div class="h-20 w-20 rounded-full border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                    <flux:avatar size="xl" class="size-full " :name="$this->court->legalCase?->client->user->name"
                                 :initials="$this->court->legalCase?->client->user->initials()"/>
                </div>
                <div class="text-sm font-semibold mt-2">{{ $this->court->legalCase?->client->user->name }}</div>
                <div class="text-xs text-gray-500">{{ $this->court->legalCase?->client->user->email }}</div>
            </div>
        </div>
    </x-slot:profile>
    @if($this->court->status == 'cancelled')
        <flux:callout icon="no-symbol" variant="danger" inline class="mt-2 mb-1">
            <flux:callout.heading>Pertemuan dibatalkan
                pada {{ $this->court->updated_at->isoFormat('dddd, D MMMM YYYY HH:mm') }} WIT
            </flux:callout.heading>
            <x-slot name="actions">
                <flux:button icon:trailing="arrow-right" variant="outline"
                             href="{{ route('staff.active.page', ['id' => $this->court->legal_case_id, 'status' => 'court-schedule']) }}">
                    Kembali
                </flux:button>
            </x-slot>
        </flux:callout>
    @endif

    <x-card :label="__('Hasil Sidang')"
            :sub-label=" __('Hasil Persidangan, Pada :date', ['date' => Carbon::parse($this->court->date)->isoFormat('dddd, D MMMM Y')])"
            main-class="flex-1">
        @if($this->courtResults->count() > 0)
            <div
                class="p-6 border border-zinc-200 dark:border-zinc-700 mt-1 rounded-lg bg-zinc-50 dark:bg-zinc-900 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($this->courtResults as $file)
                    <a
                        class="border relative size-44 overflow-hidden rounded-lg hover:scale-105 transition duration-300 ease-in-out cursor-pointer hover:shadow-lg dark:hover:shadow-none border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 flex flex-col items-center justify-center text-center p-2" href="{{ asset('storage/' . $file->file) }}" target="_blank"
                        wire:key="image-{{ $file->id }}">
                        {{ $file->name }}
                    </a>
                @endforeach
            </div>
        @else
            <div
                class="min-h-[500px] border border-zinc-200 dark:border-zinc-700 mt-1 rounded-lg bg-zinc-50 dark:bg-zinc-900 my-auto flex justify-center items-center">
                <flux:heading size="lg" level="1">Belum ada hasil sidang</flux:heading>
            </div>
        @endif
    </x-card>

    <flux:modal name="modal-upload" class="md:w-lg" @close="__reset">
        <div class="space-y-6 mb-6">
            <div>
                <flux:heading size="lg" level="1">Unggah Hasil Persindangan</flux:heading>
                <flux:text class="mt-2">Tarik dan lepas foto ke dalam kotak atau cari berkas melalui browser</flux:text>
            </div>
            <form wire:submit.prevent="store">
                <x-filepond label="Hasil Persindangan" wire:model="files" multiple accept="application/pdf, application/doc, application/docx"/>
                <div class="flex gap-2 mt-4">
                    <flux:spacer/>
                    <flux:modal.close>
                        <flux:button variant="ghost">Batal</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary">Unggah</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

</x-partials.sidebar>
