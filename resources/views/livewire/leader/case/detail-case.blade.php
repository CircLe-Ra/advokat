<?php

use App\Facades\PusherBeams;
use App\Models\Lawyer;
use App\Models\LegalCase;
use App\Models\LegalCaseValidation;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;

new
#[\Livewire\Attributes\Title('Detail Kasus')]
class extends Component {
    public ?int $id = null;
    public string $queryStatus = '';
    public string $statusCase = '';
    public string $reason = '';
    public string $lawyer = '';

    public function mount($id, $status)
    {
        $this->id = $id;
        $this->queryStatus = $status;
    }

    #[Computed]
    public function lawyers(): Collection
    {
        return Lawyer::all();
    }

    public function __reset(): void
    {
        $this->reset(['id', 'statusCase', 'reason', 'lawyer']);
        $this->resetValidation(['id', 'statusCase', 'reason', 'lawyer']);
    }

    public function verify(): void
    {
        $this->validate([
            'statusCase' => ['required'],
            'lawyer' => ['nullable', 'required_if:statusCase,accepted'],
            'reason' => ['nullable', 'required_if:statusCase,rejected'],
        ]);
        try {
            $data = [];
            if ($this->statusCase == 'accepted') {
                $data = [
                    'lawyer_id' => $this->lawyer,
                    'status' => $this->statusCase,
                ];
            }else{
                $data = [
                    'status' => $this->statusCase,
                ];
            }

            $case = LegalCase::find($this->id);
            $case->update($data);
            LegalCaseValidation::create([
                'legal_case_id' => $this->id,
                'user_id' => auth()->user()->id,
                'date_time' => now(),
                'comment' => $this->reason,
                'validation' => $this->statusCase,
            ]);
            $statusRedirect = $this->statusCase;

            if ($this->statusCase == 'rejected') {
                $body = 'Kasus tidak dapat diverifikasi. Alasan penolakan: ' . $this->reason;
            } else {
                $body = 'Kasus diterima.';
            }

            PusherBeams::send(
                user_id: $case->client->user_id,
                title: 'Konformasi Kasus',
                body: $body,
                deep_link: route('client.case'),
                is_user: true
            );

            $allStaff = User::whereHas('roles', function ($query) {
                $query->where('name', 'staf');
            })->get();
            foreach ($allStaff as $staff) {
                PusherBeams::send($staff->id, 'Konfirmasi Kasus', $body, route('staff.case.validation', $statusRedirect), true);
            }

            $this->__reset();
            Flux::modal('modal-verify')->close();
            $this->dispatch('toast', message: 'Berhasil diverifikasi');
            $this->redirectIntended(route('leader.case.validation', $statusRedirect), navigate: true);
        } catch (\Exception $e) {
            dd($e->getMessage());
            $this->dispatch('toast', message: $e->getMessage(), type: 'error', duration: 5000);
        }
    }

}; ?>

<x-partials.sidebar :back="route('leader.case.validation', ['status' => $this->queryStatus])" position="right"
                    menu="leader-case"
                    :active="'Pengajuan Kasus / Status Kasus / ' . __($this->queryStatus) . ' / Detail Kasus'">
    @if ($this->queryStatus == 'verified')
        <x-slot:action>
            <flux:modal.trigger name="modal-verify">
                <flux:button variant="primary" size="sm" icon="check-badge" icon:variant="micro" class="cursor-pointer">
                    Verifikasi
                </flux:button>
            </flux:modal.trigger>
        </x-slot:action>
    @endif

    <livewire:detail-staff-case :id="$this->id" :status="$this->queryStatus"/>

    <flux:modal name="modal-verify" class="md:w-96" @close="__reset">
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
                    <flux:select.option value="rejected">Tolak Kasus</flux:select.option>
                    <flux:select.option value="accepted">Terima Kasus</flux:select.option>
                </flux:select>
                <div wire:show="statusCase == 'rejected'">
                    <flux:textarea wire:model="reason" label="Alasan Penolakan"/>
                </div>
                <div class="space-y-6" wire:show="statusCase == 'accepted'">
                    <flux:select wire:model="lawyer" label="Pengacara">
                        <flux:select.option value="">Pilih?</flux:select.option>
                        @if($this->lawyers?->count())
                            @foreach($this->lawyers as $lawyer)
                                <flux:select.option
                                    value="{{ $lawyer->id }}">{{ $lawyer->user->name }}</flux:select.option>
                            @endforeach
                        @endif
                    </flux:select>
                    <flux:textarea wire:model="reason" label="Catatan"/>
                </div>
                <div class="flex gap-2 justify-end">
                    <flux:modal.close>
                        <flux:button variant="filled">Batal</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary">Verifikasi</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</x-partials.sidebar>
