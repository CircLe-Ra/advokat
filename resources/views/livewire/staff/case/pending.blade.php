<?php

use App\Facades\PusherBeams;
use App\Models\Client;
use App\Models\LegalCase;
use App\Models\LegalCaseDocument;
use App\Models\LegalCaseValidation;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new class extends Component {
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
    public $client;

    public $showProfilModal = false;

    #[\Livewire\Attributes\Computed]
    public function cases()
    {
        return LegalCase::where('status', 'pending')
            ->where(function ($query) {
                $query->where('number', 'like', '%' . $this->search . '%')
                    ->orWhere('title', 'like', '%' . $this->search . '%')
                    ->orWhere('summary', 'like', '%' . $this->search . '%');
            })->latest()->paginate($this->show, pageName: 'pending-page');
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

            unset($this->cases);
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

    public function showProfil($clien_id)
    {
        $this->client = Client::find($clien_id);
        Flux::modal('modal-show-client')->show();
    }
}; ?>

<x-partials.sidebar position="right" menu="staff-case" active="Pengajuan Kasus / Status Kasus / Menunggu">
    <x-table thead="#, Nomor, Kasus, Jenis, Tanggal Pengajuan, Status" :action="true"
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
                        <flux:dropdown position="bottom" align="center">
                            <flux:button icon:trailing="ellipsis-vertical" variant="ghost"></flux:button>

                            <flux:menu>
                                <flux:menu.group heading="Kasus">
                                    <flux:menu.item wire:navigate
                                                    href="{{ route('staff.case.detail-case', ['id' => $case->id, 'status' => 'pending']) }}"
                                                    icon="document-chart-bar" icon:trailing="arrow-up-right">Lihat
                                        Detail
                                    </flux:menu.item>
                                </flux:menu.group>
                                <flux:menu.group heading="Klien">
                                    <flux:menu.item icon="user" icon:trailing="arrow-up-right"
                                                    wire:click="showProfil({{ $case->client_id }})">Data Klien
                                    </flux:menu.item>
                                </flux:menu.group>

                            </flux:menu>
                        </flux:dropdown>
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

    <flux:modal name="modal-show-client" class="md:w-7xl" @close="$wire.client = null">
        <div class="space-y-6 mb-6">
            <div>
                <flux:heading size="lg" level="1">Informasi Detail Klien</flux:heading>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="border border-zinc-200 p-2">
                    <flux:label>Nama</flux:label>
                    <p class="text-gray-700">{{ $this->client->user->name ?? '-' }}</p>
                </div>
                <div class="border border-zinc-200 p-2">
                    <flux:label>Email</flux:label>
                    <p class="text-gray-700">{{ $this->client->user->email ?? '-' }}</p>
                </div>
                <div class="border border-zinc-200 p-2">
                    <flux:label>Nomor Identitas</flux:label>
                    <p class="text-gray-700">{{ $this->client->identity ?? '' }}</p>
                </div>
                <div class="border border-zinc-200 p-2">
                    <flux:label>Nomor Telepon</flux:label>
                    <p class="text-gray-700">{{ $this->client->phone ?? '' }}</p>
                </div>
                <div class="border border-zinc-200 p-2">
                    <flux:label>Foto Identitas</flux:label>
                    <img src="{{ asset('storage/' . $this->client?->identity_image) }}" alt="Foto Identitas" class="w-40 rounded border">
                </div>
                <div class="border border-zinc-200 p-2">
                    <flux:label>Foto Klien</flux:label>
                    <img src="{{ asset('storage/' . $this->client?->client_image ?? '') }}" alt="Foto Klien" class="w-40 rounded border">
                </div>
                <div class="border border-zinc-200 p-2">
                    <flux:label>Tempat Lahir</flux:label>
                    <p class="text-gray-700">{{ $this->client->place_of_birth ?? '' }}</p>
                </div>
                <div class="border border-zinc-200 p-2">
                    <flux:label>Tanggal Lahir</flux:label>
                    <p class="text-gray-700">{{ \Carbon\Carbon::parse($this->client->date_of_birth ?? '0000-00-00')->translatedFormat('d F Y') }}</p>
                </div>
                <div class="border border-zinc-200 p-2">
                    <flux:label>Alamat</flux:label>
                    <p class="text-gray-700">{{ $this->client->address ?? '' }}</p>
                </div>
            </div>
        </div>
    </flux:modal>

</x-partials.sidebar>
