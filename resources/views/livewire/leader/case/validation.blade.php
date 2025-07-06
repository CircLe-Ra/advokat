<?php

use App\Models\LegalCase;
use App\Models\LegalCaseDocument;
use App\Models\LegalCaseValidation;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new
#[\Livewire\Attributes\Title('Penganuan Kasus')]
class extends Component {

    public string $queryStatus = '';

    public function mount($status): void
    {
        $this->queryStatus = $status;
    }

}; ?>

<div>
    @livewire('leader.case.' . $this->queryStatus)
</div>
