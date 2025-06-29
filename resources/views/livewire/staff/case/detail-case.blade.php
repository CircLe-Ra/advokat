<?php

use Livewire\Volt\Component;

new
#[\Livewire\Attributes\Title('Detail Kasus')]
class extends Component {
    public ?int $id = null;

    public function mount($id): void
    {
        $this->id = $id;
    }

}; ?>

<x-partials.sidebar position="right" menu="staff-case" active="Pengajuan Kasus / Status Kasus / Detail Kasus">
    <livewire:detail-case :id="$this->id" />
</x-partials.sidebar>
