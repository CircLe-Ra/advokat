<?php

use App\Models\LegalCase;
use App\Models\LegalCaseDocument;
use App\Models\LegalCaseValidation;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new
#[\Livewire\Attributes\Title('Klien - Penganan Kasus')]
class extends Component {

    public ?int $id = null;
    public string $queryStatus = '';

    public function mount($case, $status): void
    {
        $this->id = $case;
        $this->queryStatus = $status;
    }

}; ?>

<div>
    @livewire('client.handling.' . $this->queryStatus, ['id' => $this->id])
</div>
