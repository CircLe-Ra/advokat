@props([
    'wireModels' => 'name',
])

<div id="save-animate" hidden
     class="flex justify-between items-center border border-zinc-900 px-4 py-2 rounded-xl bg-zinc-900 ">
    <flux:heading class="text-zinc-100">Belum disimpan!</flux:heading>
    <flux:button type="submit" size="sm" class="bg-zinc-100 hover:bg-zinc-200 cursor-pointer" {{ $attributes->whereStartsWith('wire:click') }}>Simpan</flux:button>
</div>

@script
<script>
    window.addEventListener('livewire:navigated', () => {
        let isButtonVisible = false;
        $js('fieldChanged', () => {
            if ($wire.name.length > 0) {
                if (!isButtonVisible) {
                    document.querySelector('#save-animate').hidden = false;
                    window.animate('#save-animate', {
                        opacity: [0, 1],
                        y: [20, 0]
                    }, {
                        duration: 0.3,
                        easing: [0.16, 1, 0.3, 1]
                    });
                    isButtonVisible = true;
                }
            } else {
                document.querySelector('#save-animate').hidden = true;
                isButtonVisible = false;
            }
        });
        $js('closeModal', () => {
            $wire.name = '';
            document.querySelector('#save-animate').hidden = true;
            isButtonVisible = false;
        })
    }, { once: true });
</script>
@endscript
