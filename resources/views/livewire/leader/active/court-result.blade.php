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

    public function mount($case): void
    {
        $court = CourtSchedule::find($case);
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

<x-partials.sidebar :id-detail="$this->court->legalCase?->id" menu="leader-active-case" :back="route('leader.active.case.page', ['case' => $this->court->legal_case_id, 'status' => 'court-schedule'], absolute: false)"
                    active="Penanganan Kasus / Jadwal Sidang / {{ $this->court->legalCase?->title }} / Hasil Sidang">
    <x-slot:information>
        <div class="flex flex-col border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 flex-shrink-0">
            <div
                class="flex flex-col items-center border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 w-full py-6 px-4 rounded-lg">
                <flux:heading size="xl" class="text-center text-xl mb-2">PENGACARA</flux:heading>
                <div class="h-20 w-20 rounded-full border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                    <flux:avatar size="xl" class="size-full " :name="$this->court->legalCase?->lawyer->user->name"
                                 :initials="$this->court->legalCase?->lawyer->user->initials()"/>
                </div>
                <div class="text-sm font-semibold mt-2">{{ $this->court->legalCase?->lawyer->user->name }}</div>
                <div class="text-xs text-gray-500">{{ $this->court->legalCase?->lawyer->user->email }}</div>
            </div>
        </div>
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
    </x-slot:information>
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
    <x-slot name="actionHead">
        <table class="w-full text-sm text-left rtl:text-right text-zinc-500 dark:text-zinc-400 mb-2">
            <thead class="text-xs text-zinc-700 uppercase bg-zinc-200 dark:bg-zinc-700 dark:text-zinc-100 ">
            <tr>
                <th scope="col" colspan="6" class="px-6 py-3 text-center border-b border-zinc-300 dark:border-zinc-500">
                    Informasi Kasus
                </th>
            </tr>
            <tr>
                <th scope="col" class="px-6 py-3">
                    No. Kasus
                </th>
                <th scope="col" class="px-6 py-3">
                    No. Perkara
                </th>
                <th scope="col" class="px-6 py-3">
                    Nama
                </th>
                <th scope="col" class="px-6 py-3">
                    Jenis
                </th>
                <th scope="col" class="px-6 py-3">
                    Tanggal Pengajuan
                </th>
                <th scope="col" class="px-6 py-3">
                    Status
                </th>
            </tr>
            </thead>
            <tbody>
            <tr class="bg-white border-b dark:bg-zinc-800 dark:border-zinc-700 border-zinc-200 ">
                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                    <flux:tooltip content="{{$this->court->legalCase?->number}}">
                        <flux:button variant="subtle"
                                     class="dark:bg-zinc-800 dark:hover:bg-zinc-800"> {{ Str::limit($this->court->legalCase?->number, 12, '...') }}</flux:button>
                    </flux:tooltip>
                </th>
                <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                    <flux:tooltip content="{{$this->court->legalCase?->number_case}}">
                        <flux:button variant="subtle"
                                     class="dark:bg-zinc-800 dark:hover:bg-zinc-800">{{ $this->court->legalCase?->number_case ? Str::limit($this->court->legalCase?->number_case, 12, '...') : '-' }}</flux:button>
                    </flux:tooltip>
                </th>
                <td class="px-6 py-4">
                    {{ $this->court->legalCase?->title }}
                </td>
                <td class="px-6 py-4">
                    {{ $this->court->legalCase?->type == 'civil' ? 'Perdata' : 'Pidana' }}
                </td>
                <td class="px-6 py-4 text-nowrap">
                    {{ $this->court->legalCase?->created_at->isoFormat('D MMMM Y HH:mm') }} WIT
                </td>
                <td class="px-6 py-4">
                    <x-badge :status="$this->court->legalCase?->status"/>
                </td>
            </tr>
            </tbody>
        </table>
    </x-slot>
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
