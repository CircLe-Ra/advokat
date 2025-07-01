<?php

use App\Models\LegalCase;
use App\Models\LegalCaseDocument;
use App\Models\LegalCaseValidation;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new
#[\Livewire\Attributes\Title('Kasus')]
class extends Component {
    use WithFileUploads;
    use WithPagination;

    #[\Livewire\Attributes\Url(history: true, keep: true)]
    public $show = 5;
    #[\Livewire\Attributes\Url(history: true, keep: true)]
    public string $search = '';

    public ?int $id = null;
    public string $file_open = '';
    public string $status = '';
    public string $reason = '';

    #[\Livewire\Attributes\Computed]
    public function cases()
    {
        return LegalCase::where('status', 'pending')->latest()->paginate($this->show, pageName: 'verified-page');
    }

    public function __reset(): void
    {
        $this->reset(['id', 'status', 'reason']);
        $this->dispatch('pond-reset');
        $this->resetValidation(['id', 'status', 'reason']);
    }

    public function verify($id): void
    {
        $this->id = $id;
        Flux::modal('modal-verify')->show();
    }

    public function store(): void
    {
        $this->validate([
            'status' => 'required',
            'reason' => ['nullable', 'required_if:status,revision'],
        ]);
        try {
            LegalCaseValidation::create([
                'legal_case_id' => $this->id,
                'user_id' => auth()->user()->id,
                'date_time' => now(),
                'comment' => $this->reason,
                'validation' => $this->status,
            ]);

            $case = LegalCase::find($this->id);
            $case->update([
                'status' => $this->status,
            ]);
            $this->__reset();
            $this->dispatch('toast', message: 'Pengajuan kasus berhasil diajukan');
            if ($this->status == 'verified') {
                $this->redirect(route('staff.case.verified'));
            }
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error', duration: 5000);
        }
    }

    public function showFile($id): void
    {
        $this->file_open = LegalCaseDocument::where('id', $id)->first()->file;
        Flux::modal('modal-show-image')->show();
    }
}; ?>

<x-partials.sidebar position="right" menu="staff-case" active="Pengajuan Kasus / Status Kasus / Menunggu">
    <x-table thead="#, Nomor, Nama, Jenis, Tanggal Pengajuan, Status" :action="true"
             label="Pengajuan Kasus" sub-label="Informasi tentang kasus yang diajukan.">
        <x-slot name="filter">
            <x-filter wire:model.live="show"/>
            <flux:input wire:model.live="search" size="sm" placeholder="Cari" class="w-full max-w-[220px]"/>
        </x-slot>
        @if($this->cases->count())
            @foreach($this->cases as $case)
                <tr class="bg-white border-b dark:bg-zinc-800 dark:border-zinc-700 border-zinc-200 ">
                    <th scope="col" class="px-6 py-3">
                        {{ $loop->iteration }}
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                        <flux:tooltip content="{{$case->number}}">
                            <flux:button variant="subtle"
                                         class="dark:bg-zinc-800 dark:hover:bg-zinc-800"> {{ Str::limit($case->number, 12, '...') }}</flux:button>
                        </flux:tooltip>
                    </th>
                    <td class="px-6 py-4">
                        {{ $case->title }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $case->type == 'civil' ? 'Perdata' : 'Pidana' }}
                    </td>
                    <td class="px-6 py-4 text-nowrap">
                        {{ $case->created_at->isoFormat('D MMMM Y HH:mm') }} WIT
                    </td>
                    <td class="px-6 py-4">
                        <x-badge :status="$case->status"/>
                    </td>
                    <td class="px-6 py-4">
                        <flux:button size="sm" variant="primary" icon:trailing="check-badge"
                                     wire:click="verify({{$case->id}})">
                            Verifikasi
                        </flux:button>
                    </td>
                </tr>
            @endforeach
        @else
            <tr class="bg-white border-b dark:bg-zinc-800 dark:border-zinc-700 border-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-600">
                <td colspan="7" class="px-6 py-4 text-center">
                    Tidak ada data
                </td>
            </tr>
        @endif
    </x-table>
    <flux:modal name="modal-show-image" class="md:w-7xl">
        <div class="space-y-6 mb-6">
            <div>
                <flux:heading size="lg" level="1">Dokumen Pendukung</flux:heading>
            </div>
            @if($this->file_open)
                <div>
                    <img class="object-cover w-full" src="{{ asset('storage/'.$this->file_open) }}" alt="">
                </div>
            @endif
        </div>
    </flux:modal>
    <flux:modal name="modal-verify" class="md:w-7xl">
        <div class="space-y-6 mb-6">
            <div>
                <flux:heading size="lg" level="1">Verifikasi Kasus</flux:heading>
                <flux:text class="text-zinc-600 dark:text-zinc-400">Periksa kembali informasi pengajuan kasus dan
                    dokumen pendukung sebelum memverifikasi kasus.
                </flux:text>
            </div>
            <form wire:submit="store">
                <flux:select wire:model="status" label="Status Kasus">
                    <flux:select.option value="">Pilih?</flux:select.option>
                    <flux:select.option value="verified">Verified</flux:select.option>
                    <flux:select.option value="revision">Revisi</flux:select.option>
                </flux:select>
                <flux:textarea wire:model="reason" label="Catatan"/>
                <div class="flex gap-2">
                    <flux:button type="button" variant="secondary" wire:click="closeModal">Batal</flux:button>
                    <flux:button type="submit" variant="primary" wire:click="verifyCase">Verifikasi</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</x-partials.sidebar>
