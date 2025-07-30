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

    #[On('trix_value_updated')]
    public function setNotes($value)
    {
        $this->notes = $value;
    }

    public function cancel(): void
    {
        $this->meeting->update([
            'status' => 'cancelled'
        ]);
        $this->dispatch('toast', message: 'Pertemuan dibatalkan');
        $this->redirectIntended(route('staff.active.page', ['id' => $this->meeting->legal_case_id, 'status' => 'schedule'], absolute: false), navigate: true);
    }

    public function save(): void
    {
        $this->validate([
            'file_submission_deadline' => 'required_if:file_collection,true',
            'notes' => 'nullable',
        ]);
        try{
            $this->meeting->update([
                'notes' => $this->notes,
                'file_collection' => $this->file_collection ? 'yes' : 'no',
                'file_submission_deadline' => $this->file_collection ? $this->file_submission_deadline : null,
                'status' => 'finished'
            ]);
            $this->dispatch('toast', message: 'Rangkuman hasil pertemuan berhasil disimpan');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }
    }

}; ?>

<x-partials.sidebar :back="route('staff.active.page', ['id' => $this->meeting->legal_case_id, 'status' => 'schedule'], absolute: false)" :id-detail="$this->meeting->legalCase?->id" menu="staff-active-case"
                    active="Penanganan Kasus / Jadwal Pertemuan / {{ $this->meeting->legalCase?->title }} / Hasil Pertemuan">
    <x-slot:profile>
        <div class="flex flex-col  dark:border-zinc-700 dark:bg-zinc-800 flex-shrink-0">
            <div
                class="flex flex-col items-center border border-zinc-100 bg-zinc-100 shadow-md dark:border-zinc-700 dark:bg-zinc-900 w-full py-6 px-4 rounded-lg">
                <flux:heading size="xl" class="text-center text-xl mb-2">PENGACARA</flux:heading>
                <div class="h-20 w-20 rounded-full border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                    <flux:avatar size="xl" class="size-full " :name="$this->meeting->legalCase?->lawyer->user->name"
                                 :initials="$this->meeting->legalCase?->lawyer->user->initials()"/>
                </div>
                <div class="text-sm font-semibold mt-2">{{ $this->meeting->legalCase?->lawyer->user->name }}</div>
                <div class="text-xs text-gray-500">{{ $this->meeting->legalCase?->lawyer->user->email }}</div>
            </div>
        </div>
    </x-slot:profile>
    <x-slot:action>
        <flux:button :disabled="$this->meeting->status == 'cancelled' || $this->meeting->status == 'finished' || $this->meeting->meetingFileAdditions->count() > 0"
                     variant="danger" size="sm" icon="no-symbol" icon:variant="micro" class="cursor-pointer mr-2 disabled:cursor-not-allowed"
                     wire:click="cancel" wire:confirm="Anda yakin ingin membatalkan pertemuan ini?">
            Dibatalkan
        </flux:button>
        <flux:button :disabled="$this->meeting->status == 'cancelled' || $this->meeting->meetingFileAdditions->count() > 0" variant="primary" size="sm"
                     icon="hard-drive-download" icon:variant="micro" class="cursor-pointer" wire:click="save">
            Simpan
        </flux:button>
    </x-slot:action>
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
    <div class="flex gap-2 justify-between">
        <div>
            <div class="w-full max-w-sm">
                <div class="p-6 border border-zinc-100 dark:border-zinc-700 rounded-lg bg-zinc-100 shadow-md dark:bg-zinc-900">
                    <flux:heading size="xl" level="1">Hasil Pertemuan</flux:heading>
                    <flux:text class="text-zinc-600 dark:text-zinc-400 mt-2 mb-6">
                        Hasil pertemuan klien dengan pengacara.
                    </flux:text>
                    <flux:fieldset>
                        <div class="space-y-4">
                            <flux:switch :disabled="$this->meeting->meetingFileAdditions->count() > 0" wire:model="file_collection" label="Penambahan Berkas?"
                                         description="Nyalakan jika ada penamahan berkas untuk klien."/>
                        </div>
                        <div class="mt-4" wire:show="file_collection" wire:cloak wire:transition.scale.origin.top>
                            <flux:input :disabled="$this->meeting->meetingFileAdditions->count() > 0" type="date" wire:model="file_submission_deadline"
                                        label="Tentukan Tenggat Waktu Pengumpulan Berkas"/>
                        </div>
                    </flux:fieldset>
                </div>
                @if($this->meeting->meetingFileAdditions->count())
                    <div class="p-6 border border-zinc-200 dark:border-zinc-700 mt-2 rounded-lg bg-white dark:bg-zinc-900">
                        <flux:heading size="xl" level="1">Penambahan Berkas</flux:heading>
                        <flux:text class="text-zinc-600 dark:text-zinc-400 mt-2 mb-4">
                            Berkas yang telah anda ditambahkan.
                        </flux:text>
                        <ul class="text-zinc-900 bg-white border border-zinc-300 rounded-lg dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                            <li class="w-full px-4 py-3 text-sm font-bold border-b  border-zinc-300 rounded-t-lg dark:border-zinc-600 flex items-center justify-between">
                                Dokumen
                                <flux:icon.document class="size-4" />
                            </li>
                            @foreach($this->meeting->meetingFileAdditions as $document)
                                <li class="flex justify-between w-full px-4 py-2 items-center {{ $loop->last ? '' : 'border-b border-zinc-300 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white' }}">
                                    <a target="_blank" href="{{ asset('storage/' . $document->file) }}" class=" items-center space-x-2 hover:underline ">
                                        <span>
                                            @if($document->type == 'pdf')
                                                Lihat File PDF
                                            @elseif($document->type == 'xls' || $document->type == 'xlsx')
                                                Lihat File Excel
                                            @elseif($document->type == 'doc' || $document->type == 'docx')
                                                Lihat File Word
                                            @else
                                                Lihat File Gambar
                                            @endif
                                        </span>
                                    </a>
                                    <flux:icon.arrow-up-right class="size-4" />
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
        <div>
            <div class="p-6 border border-zinc-100 dark:border-zinc-700 rounded-lg bg-zinc-100 shadow-md dark:bg-zinc-900">
                <flux:heading size="xl" level="1">Hasil Pertemuan</flux:heading>
                <flux:subheading class="mb-4">Hasil pertemuan klien dengan pengacara.</flux:subheading>
                <livewire:trix-editor :value="$this->notes" />
            </div>
        </div>
    </div>
</x-partials.sidebar>
