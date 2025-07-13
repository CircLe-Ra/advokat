<?php

use App\Models\LegalCase;
use App\Models\LegalCaseDocument;
use App\Models\LegalCaseValidation;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new
#[\Livewire\Attributes\Title('Penganan Kasus')]
class extends Component {

    public ?int $id = null;
    public string $queryStatus = '';

    public function mount($id, $status): void
    {
        $this->id = $id;
        $this->queryStatus = $status;
    }

}; ?>


<div>
    @livewire('lawyer.' . $this->queryStatus, ['id' => $this->id])
</div>
