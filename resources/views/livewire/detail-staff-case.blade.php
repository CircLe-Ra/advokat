<?php

use App\Models\LegalCase;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use Carbon\Carbon;

new
#[Title('Detail Kasus')]
class extends Component {

    public LegalCase $case;
    public string $status = '';

    public function mount($id, $status): void
    {
        $this->case = LegalCase::find($id);
        $this->status = $status;
    }
}; ?>

<x-card main-class="mb-4" label="Detail Kasus" sub-label="Informasi tentang kasus yang diajukan.">
    <div class="grid grid-cols-3 gap-2">
        <div class="space-y-2">
            <ul class="text-zinc-900 bg-white border border-zinc-300 rounded-lg dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                <li class="w-full px-4 py-3 text-sm font-bold border-b border-zinc-300 rounded-t-lg dark:border-zinc-600 flex items-center justify-between">
                    Nomor Kasus
                    <flux:icon.file-digit class="size-4" />
                </li>
                <li class="w-full px-4 py-2 flex items-center justify-center">{{ $this->case->number }}</li>
            </ul>
            <ul class="text-zinc-900 bg-white border border-zinc-300 rounded-lg dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                <li class="w-full px-4 py-3 text-sm font-bold border-b  border-zinc-300 rounded-t-lg dark:border-zinc-600 flex items-center justify-between">
                    Jenis Kasus
                    <flux:icon.file-type-2 class="size-4" />
                </li>
                <li class="w-full px-4 py-2 flex items-center justify-center">{{ $this->case->type == 'civil' ? 'Perdata' : 'Pidana' }}</li>
            </ul>
            <ul class="text-zinc-900 bg-white border border-zinc-300 rounded-lg dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                <li class="w-full px-4 py-3 text-sm font-bold border-b  border-zinc-300 rounded-t-lg dark:border-zinc-600 flex items-center justify-between">
                    Kasus
                    <flux:icon.captions class="size-4" />
                </li>
                <li class="w-full px-4 py-2 flex items-center justify-center">{{ $this->case->title }}</li>
            </ul>
            <ul class="text-zinc-900 bg-white border border-zinc-300 rounded-lg dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                <li class="w-full px-4 py-3 text-sm font-bold border-b  border-zinc-300 rounded-t-lg dark:border-zinc-600 flex items-center justify-between">
                    Status Kasus
                    <flux:icon.chart-line class="size-4" />
                </li>
                <li class="w-full px-4 py-2 flex items-center justify-center">
                    @php
                        $status = match ($this->case->status) {
                            'draft' => 'Draft',
                            'pending' => 'Menunggu',
                            'verified' => 'Diverifikasi',
                            'accepted' => 'Diterima',
                            'rejected' => 'Ditolak',
                            'closed' => 'Ditutup',
                            default => 'Draft',
                        };
                        echo $status;
                    @endphp
                </li>
            </ul>
            <ul class="text-zinc-900 bg-white border border-zinc-300 rounded-lg dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                <li class="w-full px-4 py-3 text-sm font-bold border-b  border-zinc-300 rounded-t-lg dark:border-zinc-600 flex items-center justify-between">
                    Tanggal Kasus Dibuat
                    <flux:icon.calendar-days class="size-4" />
                </li>
                <li class="w-full px-4 py-2 flex items-center justify-center">{{ $this->case->created_at->isoFormat('dddd, D MMMM Y') }}</li>
            </ul>
            <ul class="text-zinc-900 bg-white border border-zinc-300 rounded-lg dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                <li class="w-full px-4 py-3 text-sm font-bold border-b  border-zinc-300 rounded-t-lg dark:border-zinc-600 flex items-center justify-between">
                    Dokumen
                    <flux:icon.document class="size-4" />
                </li>
                @if ($this->case->documents)
                    @foreach($this->case->documents as $document)
                        <li class=" w-full px-4 py-2 items-center {{ $loop->last ? '' : 'border-b border-zinc-300 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white' }}">
                            <a target="_blank" href="{{ asset('storage/' . $document->file) }}" class="flex justify-between items-center space-x-2 hover:underline">
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
                                <flux:icon.arrow-up-right class="size-4" />
                            </a>
                        </li>
                    @endforeach
                @else
                    <li class="w-full px-4 py-2 flex items-center justify-center">Tidak Ada Dokumen</li>
                @endif
            </ul>
        </div>
        <div class="col-span-2">
            <div class="flex flex-col h-full ">
                <flux:heading size="xl" class="bg-white border p-4 rounded-t-lg border-b-0 border-zinc-300 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white text-center text-xl">Ringkasan Kasus</flux:heading>
                <div class="bg-white text-justify indent-8 items-center p-6 justify-between rounded-b-lg border border-zinc-300 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                {!! $this->case->summary !!}
                </div>
                <flux:heading size="xl" class="bg-white mt-2 border p-4 rounded-t-lg border-b-0 border-zinc-300 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white text-center text-xl">Kronologi Kasus</flux:heading>
                <div class="bg-zinc-50 text-justify indent-8 items-center p-6 justify-between rounded-b-lg border border-zinc-300 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                    {!! $this->case->chronology !!}
                </div>
            </div>
        </div>
    </div>
    <div class="bg-zinc-50 mt-2 border p-4 rounded-lg border-zinc-300 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
        <flux:heading size="xl" class="text-xl text-center">Status Kasus</flux:heading>
        <flux:text class="text-zinc-900 dark:text-white text-center"><i>Timeline</i> status kasus dan informasi kasus</flux:text>
        <div class="border rounded-lg border-zinc-300 dark:border-zinc-600 p-4 mt-6 bg-white dark:bg-zinc-800">
            <ol class="relative border-s border-zinc-200 dark:border-zinc-600">
                <li class="mb-5 ms-4">
                    <div class="absolute w-3 h-3 bg-accent rounded-full mt-1.5 -start-1.5 border border-white dark:border-zinc-900"></div>
                    <time class="mb-1 text-sm font-normal leading-none text-zinc-400 dark:text-zinc-500">{{ $this->case->created_at->isoFormat('dddd, D MMMM Y HH:mm') }} WIT</time>

                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ Str::ucfirst($this->case->client->user->name) }} - <x-badge status="draft" /></h3>
                    <p class="mb-4 text-base font-normal text-zinc-500 dark:text-zinc-400">Kasus ditambahkan oleh klien.</p>
                </li>
                @if($this->case->validations->count())
                    @foreach($this->case->validations as $validation)
                        <li class="mb-5 ms-4">
                            <div class="absolute w-3 h-3 bg-accent rounded-full mt-1.5 -start-1.5 border border-white dark:border-zinc-900"></div>
                            <time class="mb-1 text-sm font-normal leading-none text-zinc-400 dark:text-zinc-500">{{ Carbon::parse($validation->date_time)->isoFormat('dddd, D MMMM Y HH:mm') }} WIT</time>

                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ Str::ucfirst($validation->user->name) }} - <x-badge :status="$validation->validation" /></h3>
                            <p class="mb-4 text-base font-normal text-zinc-500 dark:text-zinc-400">
                                {{ $validation->comment ? 'Pesan: ' . $validation->comment : ($validation->validation == 'pending' ? 'Kasus diajukan oleh klien.' : ($validation->validation == 'revised' ? 'Kasus telah direvisi oleh klien.' : '-')) }}
                            </p>
                        </li>
                    @endforeach
                @endif
            </ol>
        </div>

    </div>

</x-card>
