@props(['menuInfo' => null])
<flux:navlist wire:ignore class=" border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 p-4 rounded-lg">
    <flux:navlist.group expandable heading="Status Kasus">
        <flux:navlist.item class="py-5 text-base" :href="route('staff.case.validation', ['status' => 'pending'])" :current="isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'pending'" wire:navigate>
            <div class="absolute -inset-y-[1px] w-[2px] -start-7 ms-4 {{ isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'pending' ? 'bg-zinc-800 dark:bg-white' : 'bg-zinc-200 dark:bg-zinc-700' }}" ></div>
            Menunggu
        </flux:navlist.item>
        <flux:navlist.item class="py-5 text-base" :href="route('staff.case.validation', ['status' => 'revision'])" :current="isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'revision'" wire:navigate>
            <div class="absolute -inset-y-[1px] w-[2px] -start-7 ms-4 {{ isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'revision' ? 'bg-zinc-800 dark:bg-white' : 'bg-zinc-200 dark:bg-zinc-700' }}" ></div>
            Revisi
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
