<?php

use App\Models\MeetingFileAddition;
use App\Models\MeetingSchedule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\{Computed, On, Url, Title};
use Carbon\Carbon;

new class extends Component {
    use WithFileUploads;

    public ?int $id = null;
    public ?string $notes = null;
    public bool $file_collection = false;
    public $file_submission_deadline;
    public array $files = [];

    public $meeting;

    public function mount($id): void
    {
        $meeting = MeetingSchedule::find($id);
        $this->meeting = $meeting;
        $this->notes = $meeting->notes;
        $this->file_collection = $meeting->file_collection == 'yes';
        $this->file_submission_deadline = $meeting->file_submission_deadline;
    }


    public function save(): void
    {
        $this->validate([
            'files' => ['required'],
            'files.*' => ['max:2048'],
        ]);
        try {
            foreach ($this->files as $file) {
                MeetingFileAddition::create([
                    'meeting_schedule_id' =>  $this->meeting->id,
                    'file' => $file->store('meeting-result-addition', 'public'),
                    'type' => $file->getClientOriginalExtension(),
                ]);
            }
            $this->reset(['files']);
            $this->dispatch('pond-reset');
            $this->dispatch('toast', message: 'Penambahan berkas berhasil ditambahkan');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }
    }

    public function removeFile(MeetingFileAddition $file): void
    {
        try {
            Storage::delete($file->file);
            $file->delete();
            $this->dispatch('toast', message: 'Berkas berhasil dihapus');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }
    }

}; ?>

<x-partials.sidebar position="right"
                    :back="route('client.case.handling', ['case' => $this->meeting->legal_case_id, 'status' => 'schedule'], absolute: false)"
                    :id-detail="$this->meeting->legalCase?->id" menu="client-active-case"
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
    <div class="flex gap-3 justify-between">
        <div>
            <div class="w-full min-w-xs">
                <div class="p-6 border border-zinc-100 dark:border-zinc-700 rounded-lg bg-zinc-100 shadow-md dark:bg-zinc-900">
                    <flux:heading size="xl" level="1">Pemberkasan</flux:heading>
                    <flux:text class="text-zinc-600 dark:text-zinc-400 mt-2 mb-6">
                        Opsi penambahan berkas yang dibutuhkan.
                    </flux:text>
                    <flux:fieldset>
                        <div class="space-y-4">
                            <flux:switch disabled wire:model="file_collection" label="Penambahan Berkas?"/>
                        </div>
                        @if($this->file_collection)
                            <flux:separator class="my-4"/>
                            <div wire:show="file_collection" wire:cloak wire:transition.scale.origin.top>
                                <flux:input disabled type="date" wire:model="file_submission_deadline"
                                            label="Tenggat Waktu Pengumpulan Berkas"/>
                            </div>
                            <flux:separator class="my-4"/>
                            <x-filepond wire:model="files" label="Pengumpulan Berkas (Max 2MB)" multiple="true"/>
                            <flux:button variant="primary" wire:click="save" class="w-full">
                                Simpan
                            </flux:button>
                        @endif
                    </flux:fieldset>
                </div>
                @if($this->meeting->meetingFileAdditions->count())
                    <div class="p-6 border border-zinc-100 dark:border-zinc-700 rounded-lg bg-zinc-100 shadow-md mt-3 dark:bg-zinc-900">
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
                                        <flux:icon.x-circle class="size-5 text-red-500 cursor-pointer "  wire:click="removeFile({{ $document->id }})" wire:confirm="Yakin ingin menghapus berkas ini?"/>
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
        <div class="w-full">
            <div class="p-6 border border-zinc-100 dark:border-zinc-700 rounded-lg bg-zinc-100 shadow-md dark:bg-zinc-900">
                <flux:heading size="xl" level="1">Catatan</flux:heading>
                <flux:subheading class="mb-4">Ringkasan/Rangkuman pertemuan klien dengan pengacara.</flux:subheading>
                @if($this->notes)
                    <div class="border rounded-lg border-zinc-300 dark:border-zinc-600 p-2 prose">
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
</x-partials.sidebar>
