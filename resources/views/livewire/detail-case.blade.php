<?php

use App\Models\LegalCase;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new
#[Title('Detail Kasus')]
class extends Component {

    public LegalCase $case;

    public function mount($id): void
    {
        $this->case = LegalCase::find($id);
    }
}; ?>

<x-card label="Detail Kasus" sub-label="Informasi tentang kasus yang anda ajukan.">
    <div class="grid grid-cols-3 gap-4">
        <div class="space-y-2">
            <ul class="text-slate-900 bg-white border border-slate-200 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                <li class="w-full px-4 py-3 text-sm font-bold border-b border-slate-200 rounded-t-lg dark:border-slate-600 flex items-center justify-between">
                    Nomor Kasus
                    <flux:icon.file-digit class="size-4" />
                </li>
                <li class="w-full px-4 py-2 flex items-center justify-center">{{ $this->case->number }}</li>
            </ul>
            <ul class="text-slate-900 bg-white border border-slate-200 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                <li class="w-full px-4 py-3 text-sm font-bold border-b  border-slate-200 rounded-t-lg dark:border-slate-600 flex items-center justify-between">
                    Jenis Kasus
                    <flux:icon.file-type-2 class="size-4" />
                </li>
                <li class="w-full px-4 py-2 flex items-center justify-center">{{ $this->case->type == 'civil' ? 'Perdata' : 'Pidana' }}</li>
            </ul>
            <ul class="text-slate-900 bg-white border border-slate-200 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                <li class="w-full px-4 py-3 text-sm font-bold border-b  border-slate-200 rounded-t-lg dark:border-slate-600 flex items-center justify-between">
                    Kasus
                    <flux:icon.captions class="size-4" />
                </li>
                <li class="w-full px-4 py-2 flex items-center justify-center">{{ $this->case->title }}</li>
            </ul>
            <ul class="text-slate-900 bg-white border border-slate-200 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                <li class="w-full px-4 py-3 text-sm font-bold border-b  border-slate-200 rounded-t-lg dark:border-slate-600 flex items-center justify-between">
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
            <ul class="text-slate-900 bg-white border border-slate-200 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                <li class="w-full px-4 py-3 text-sm font-bold border-b  border-slate-200 rounded-t-lg dark:border-slate-600 flex items-center justify-between">
                    Tanggal Kasus Dibuat
                    <flux:icon.calendar-days class="size-4" />
                </li>
                <li class="w-full px-4 py-2 flex items-center justify-center">{{ $this->case->created_at->isoFormat('dddd, D MMMM Y') }}</li>
            </ul>
            <ul class="text-slate-900 bg-white border border-slate-200 rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                <li class="w-full px-4 py-3 text-sm font-bold border-b  border-slate-200 rounded-t-lg dark:border-slate-600 flex items-center justify-between">
                    Dokumen
                    <flux:icon.document class="size-4" />
                </li>
                @if ($this->case->documents)
                    @foreach($this->case->documents as $document)
                        <li class=" w-full px-4 py-2 items-center {{ $loop->last ? '' : 'border-b border-slate-600' }}">
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
                <flux:heading size="xl" class="border p-4 rounded-t-lg border-b-0 border-slate-200 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-center text-xl">Ringkasan Kasus</flux:heading>
                <div class="text-justify indent-8 items-center p-6 justify-between rounded-b-lg border border-slate-200 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                {{ $this->case->summary }}
                </div>
                <flux:heading size="xl" class="mt-2 border p-4 rounded-t-lg border-b-0 border-slate-200 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-center text-xl">Kronologi Kasus</flux:heading>
                <div class="text-justify indent-8 items-center p-6 justify-between rounded-b-lg border border-slate-200 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                    {{ $this->case->chronology }}
                </div>
            </div>
        </div>
    </div>
</x-card>
