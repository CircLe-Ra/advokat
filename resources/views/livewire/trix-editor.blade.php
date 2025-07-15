<?php

use Livewire\Volt\Component;

new class extends Component {
    const EVENT_VALUE_UPDATED = 'trix_value_updated';
    public $value;
    public $trixId;
    public $disabled;

    public function mount($value = '', $disabled = false): void
    {
        $this->value = $value;
        $this->disabled = $disabled;
        $this->trixId = 'trix-' . uniqid();
    }

    public function updatedValue($value): void
    {
        $this->dispatch(self::EVENT_VALUE_UPDATED, value: $value);
    }
}; ?>

<div wire:ignore class="trix-container">
    <input id="{{ $trixId }}" type="hidden" name="content" value="{{ $value }}">
    <trix-editor input="{{ $trixId }}"></trix-editor>
</div>
@pushonce('heads')
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
@endpushonce
@pushonce('scripts')
    @script
    <script>
        window.addEventListener('livewire:navigated', () => {
            document.querySelector('trix-editor').editor.element.setAttribute('contentEditable', $wire.disabled ? 'false' : 'true');
            addEventListener('trix-change', function(event) {
                const content = event.target.value;
                @this.set('value', content);
            });
            // document.addEventListener('trix-file-accept', function(event) {
            //     event.preventDefault();
            // });
        }, { once: true });
    </script>
    @endscript
@endpushonce
