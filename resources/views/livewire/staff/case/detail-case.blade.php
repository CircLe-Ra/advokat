<?php

use App\Facades\PusherBeams;
use App\Models\LegalCase;
use App\Models\LegalCaseValidation;
use App\Models\User;
use Livewire\Volt\Component;

new
#[\Livewire\Attributes\Title('Detail Kasus')]
class extends Component {
    public ?int $id = null;
    public string $queryStatus = '';
    public string $statusCase = '';
    public string $reason = '';

    public function mount($id, $status)
    {
        $case = LegalCase::find($id);
        if (!$case->status == 'revised') {
            if ($case->status != $status) {
                return abort(404);
            }
        }
        $this->id = $id;
        $this->queryStatus = $status;
    }

    public function __reset(): void
    {
        $this->reset(['id', 'statusCase', 'reason']);
        $this->dispatch('pond-reset');
        $this->resetValidation(['id', 'statusCase', 'reason']);
    }

    public function verify(): void
    {
        $this->validate([
            'statusCase' => ['required'],
            'reason' => ['nullable', 'required_if:statusCase,revision'],
        ]);
        try {
            $case = LegalCase::find($this->id);
            $case->update([
                'status' => $this->statusCase,
            ]);
            LegalCaseValidation::create([
                'legal_case_id' => $this->id,
                'user_id' => auth()->user()->id,
                'date_time' => now(),
                'comment' => $this->reason,
                'validation' => $this->statusCase,
            ]);

            if ($this->statusCase == 'revision') {
                $body = 'Anda harus melakukan revisi terhadap kasus ini.';
            } else {
                $body = 'Kasus berhasil diverifikasi.';
            }

            PusherBeams::send(
                user_id: $case->client->user_id,
                title: 'Konformasi Kasus',
                body: $body,
                deep_link: route('client.case'),
                is_user: true
            );

            $allLeader = User::whereHas('roles', function ($query) {
                $query->where('name', 'pimpinan');
            })->get();
            foreach ($allLeader as $leader) {
                PusherBeams::send($leader->id, 'Pengajuan Kasus Baru', $case->title, route('leader.case.validation', ['status' => 'verified']), true);
            }

            $statusRedirect = $this->statusCase;
            $this->__reset();
            $this->dispatch('toast', message: 'Berhasil diverifikasi');
            $this->redirectIntended(route('staff.case.validation', $statusRedirect), navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error', duration: 5000);
        }
    }

}; ?>

<x-partials.sidebar :back="route('staff.case.validation', ['status' => $this->queryStatus])" position="right"
                    menu="staff-case"
                    :active="'Pengajuan Kasus / Status Kasus / ' . __($this->queryStatus) . ' / Detail Kasus'">
    @if ($this->queryStatus == 'pending' || $this->queryStatus == 'revision')
        <x-slot:action>
            <flux:modal.trigger name="modal-verify">
                <flux:button variant="primary" size="sm" icon="check-badge" icon:variant="micro" class="cursor-pointer">
                    Verifikasi
                </flux:button>
            </flux:modal.trigger>
        </x-slot:action>
    @endif
    <livewire:detail-staff-case :id="$this->id" :status="$this->queryStatus"/>

    <flux:modal name="modal-verify" class="md:w-96" variant="flyout">
        <div class="space-y-6 mb-6">
            <div>
                <flux:heading size="lg" level="1">Verifikasi Kasus</flux:heading>
                <flux:text class="text-zinc-600 dark:text-zinc-400">Periksa kembali informasi pengajuan kasus dan
                    dokumen pendukung sebelum memverifikasi kasus.
                </flux:text>
            </div>
            <form wire:submit="verify" class="space-y-6">
                <flux:select wire:model="statusCase" label="Status Kasus">
                    <flux:select.option value="">Pilih?</flux:select.option>
                    <flux:select.option value="verified">Verifikasi</flux:select.option>
                    <flux:select.option value="revision">Revisi</flux:select.option>
                </flux:select>
                <flux:textarea wire:model="reason" label="Catatan"/>
                <div class="flex gap-2 justify-end">
                    <flux:button variant="filled">Batal</flux:button>
                    <flux:button type="submit" variant="primary">Verifikasi</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</x-partials.sidebar>
