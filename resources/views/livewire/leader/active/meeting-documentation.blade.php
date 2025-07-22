<?php

use App\Models\MeetingDocumentation;
use App\Models\MeetingSchedule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Carbon\Carbon;

new class extends Component {
    use WithFileUploads;

    public $meeting;
    public ?array $files = [];
    public $file;

    public function mount(MeetingSchedule $case): void
    {
        $this->meeting = $case;
    }

    #[Computed]
    public function meetingDocumentations()
    {
        return MeetingDocumentation::where('meeting_schedule_id', $this->meeting->id)->get();
    }

    public function showImage($file): void
    {
        $this->file = MeetingDocumentation::where('id', $file)->first()->file;
        Flux::modal('modal-show-image')->show();
    }

}; ?>

<x-partials.sidebar :id-detail="$this->meeting->legalCase?->id" menu="leader-active-case" :back="route('leader.active.case.page', ['case' => $this->meeting->legal_case_id, 'status' => 'schedule'], absolute: false)"
                    active="Penanganan Kasus / Jadwal Pertemuan / {{ $this->meeting->legalCase?->title }} / Hasil Pertemuan">
    <x-slot:information>
        <div class="flex flex-col border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 flex-shrink-0">
            <div
                class="flex flex-col items-center border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 w-full py-6 px-4 rounded-lg">
                <flux:heading size="xl" class="text-center text-xl mb-2">PENGACARA</flux:heading>
                <div class="h-20 w-20 rounded-full border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                    <flux:avatar size="xl" class="size-full " :name="$this->meeting->legalCase?->lawyer->user->name"
                                 :initials="$this->meeting->legalCase?->lawyer->user->initials()"/>
                </div>
                <div class="text-sm font-semibold mt-2">{{ $this->meeting->legalCase?->lawyer->user->name }}</div>
                <div class="text-xs text-gray-500">{{ $this->meeting->legalCase?->lawyer->user->email }}</div>
            </div>
        </div>
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
    </x-slot:information>
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
    <x-card :label="__('Dokumentasi Pertemuan')"
            :sub-label=" __('Dokumentasi pertemuan dengan pengacara, Pada :date', ['date' => Carbon::parse($this->meeting->date_time)->isoFormat('dddd, D MMMM Y')])"
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
                        <flux:tooltip content="{{$this->meeting->legalCase?->number}}">
                            <flux:button variant="subtle"
                                         class="dark:bg-zinc-800 dark:hover:bg-zinc-800"> {{ Str::limit($this->meeting->legalCase?->number, 12, '...') }}</flux:button>
                        </flux:tooltip>
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                        <flux:tooltip content="{{$this->meeting->legalCase?->number_case}}">
                            <flux:button variant="subtle"
                                         class="dark:bg-zinc-800 dark:hover:bg-zinc-800">{{ $this->meeting->legalCase?->number_case ? Str::limit($this->meeting->legalCase?->number_case, 12, '...') : '-' }}</flux:button>
                        </flux:tooltip>
                    </th>
                    <td class="px-6 py-4">
                        {{ $this->meeting->legalCase?->title }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $this->meeting->legalCase?->type == 'civil' ? 'Perdata' : 'Pidana' }}
                    </td>
                    <td class="px-6 py-4 text-nowrap">
                        {{ $this->meeting->legalCase?->created_at->isoFormat('D MMMM Y HH:mm') }} WIT
                    </td>
                    <td class="px-6 py-4">
                        <x-badge :status="$this->meeting->legalCase?->status"/>
                    </td>
                </tr>
                </tbody>
            </table>
        </x-slot>
        @if($this->meetingDocumentations->count() > 0)
            <div
                class="p-6 border border-zinc-200 dark:border-zinc-700 mt-1 rounded-lg bg-zinc-50 dark:bg-zinc-900 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($this->meetingDocumentations as $image)
                    <div
                        class="relative size-44 overflow-hidden rounded-lg hover:scale-105 transition duration-300 ease-in-out cursor-pointer hover:shadow-lg dark:hover:shadow-none border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900"
                        wire:key="image-{{ $image->id }}">
                        <img wire:click="showImage({{$image->id}})" class="object-cover size-44"
                             src="{{ asset('storage/'.$image->file) }}" alt="">
                    </div>
                @endforeach
            </div>
        @else
            <div
                class="min-h-[500px] border border-zinc-200 dark:border-zinc-700 mt-1 rounded-lg bg-zinc-50 dark:bg-zinc-900 my-auto flex justify-center items-center">
                <flux:heading size="lg" level="1">Belum ada dokumentasi kegiatan</flux:heading>
            </div>
        @endif
    </x-card>
    <flux:modal name="modal-show-image" class="md:w-7xl">
        <div class="space-y-6 mb-6">
            <div>
                <flux:heading size="lg" level="1">Foto Dokumentasi</flux:heading>
            </div>
            <div>
                @if($this->file)
                <img class="object-cover w-full" src="{{ asset('storage/'.$this->file) }}" alt="">
                @endif
            </div>
        </div>
    </flux:modal>
</x-partials.sidebar>
