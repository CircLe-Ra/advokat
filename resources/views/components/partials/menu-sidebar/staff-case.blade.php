@props(['menuInfo' => null])
@php
    $cases = App\Models\LegalCase::all()->map(function ($case) {
        return [
            'pending' => $case->where('status', 'pending')->count() ?? 0,
            'revised' => $case->where('status', 'revised')->count() ?? 0,
        ];
    });
@endphp
<flux:navlist wire:ignore class=" border border-zinc-100 bg-zinc-100 shadow-md dark:border-zinc-700 dark:bg-zinc-900 p-4 rounded-lg">
    <flux:navlist.group expandable heading="Status Kasus">
        <flux:navlist.item class="py-5 text-base inline-flex" :href="route('staff.case.validation', ['status' => 'pending'])" :current="isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'pending'" wire:navigate>
            <div class="absolute -inset-y-[1px] w-[2px] -start-7 ms-4 {{ isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'pending' ? 'bg-zinc-800 dark:bg-white' : 'bg-zinc-200 dark:bg-zinc-700' }}" ></div>
            <div class="flex items-center">
                Menunggu
                @if (isset($cases[0]) && isset($cases[0]['pending']) && $cases[0]['pending'] > 0)
                    <span class="absolute flex size-3 right-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-danger opacity-75"></span>
                        <span class="relative inline-flex size-3 rounded-full bg-danger-content"></span>
                    </span>
                @endif
            </div>
        </flux:navlist.item>
        <flux:navlist.item class="py-5 text-base" :href="route('staff.case.validation', ['status' => 'revision'])" :current="isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'revision'" wire:navigate>
            <div class="absolute -inset-y-[1px] w-[2px] -start-7 ms-4 {{ isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'revision' ? 'bg-zinc-800 dark:bg-white' : 'bg-zinc-200 dark:bg-zinc-700' }}" ></div>
            <div class="flex items-center">
                Revisi
                @if (isset($cases[0]) && isset($cases[0]['revised']) && $cases[0]['revised'] > 0)
                    <span class="absolute flex size-3 right-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-danger opacity-75"></span>
                        <span class="relative inline-flex size-3 rounded-full bg-danger-content"></span>
                    </span>
                @endif
            </div>
        </flux:navlist.item>
        <flux:navlist.item class="py-5 text-base" :href="route('staff.case.validation', ['status' => 'verified'])" :current="isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'verified'" wire:navigate>
            <div class="absolute -inset-y-[1px] w-[2px] -start-7 ms-4 {{ isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'verified' ? 'bg-zinc-800 dark:bg-white' : 'bg-zinc-200 dark:bg-zinc-700' }}" ></div>
            Terverifikasi
        </flux:navlist.item>
        <flux:navlist.item class="py-5 text-base" :href="route('staff.case.validation', ['status' => 'rejected'])" :current="isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'rejected'" wire:navigate>
            <div class="absolute -inset-y-[1px] w-[2px] -start-7 ms-4 {{ isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'rejected' ? 'bg-zinc-800 dark:bg-white' : 'bg-zinc-200 dark:bg-zinc-700' }}" ></div>
            Ditolak
        </flux:navlist.item>
        <flux:navlist.item class="py-5 text-base" :href="route('staff.case.validation', ['status' => 'accepted'])" :current="isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'accepted'" wire:navigate>
            <div class="absolute -inset-y-[1px] w-[2px] -start-7 ms-4 {{ isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'accepted' ? 'bg-zinc-800 dark:bg-white' : 'bg-zinc-200 dark:bg-zinc-700' }}" ></div>
            Diterima / Berjalan
        </flux:navlist.item>
        <flux:navlist.item class="py-5 text-base" :href="route('staff.case.validation', ['status' => 'closed'])" :current="isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'closed'" wire:navigate>
            <div class="absolute -inset-y-[1px] w-[2px] -start-7 ms-4 {{ isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'closed' ? 'bg-zinc-800 dark:bg-white' : 'bg-zinc-200 dark:bg-zinc-700' }}" ></div>
            Ditutup / Selesai
        </flux:navlist.item>
    </flux:navlist.group>
</flux:navlist>
@isset($menuInfo)
    {{ $menuInfo }}
@endisset
