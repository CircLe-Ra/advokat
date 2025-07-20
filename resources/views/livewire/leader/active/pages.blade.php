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

    public $case;
    public string $queryStatus = '';

    public function mount($case, $status): void
    {
        $this->case = $case;
        $this->queryStatus = $status;
    }

}; ?>

<div>
    @livewire('leader.active.' . $this->queryStatus, ['case' => $this->case])
</div>
