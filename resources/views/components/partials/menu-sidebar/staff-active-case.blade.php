@props(['menuInfo' => null, 'idDetail' => null])
<flux:navlist wire:ignore class=" border border-zinc-100 bg-zinc-100 shadow-md dark:border-zinc-700 dark:bg-zinc-900 p-4 rounded-lg">
    <flux:navlist.group expandable heading="Kemajuan Kasus">
        <flux:navlist.item class="py-5 text-base" :href="route('staff.active.page', ['id' => $idDetail, 'status' => 'schedule'])" :current="isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'schedule' || isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'meeting-result' || isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'meeting-documentation'" wire:navigate>
            <div class="absolute -inset-y-[1px] w-[2px] -start-7 ms-4 {{ isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'schedule' || isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'meeting-result' || isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'meeting-documentation' ? 'bg-zinc-800 dark:bg-white' : 'bg-zinc-200 dark:bg-zinc-700' }}" ></div>
            Jadwal Pertemuan
        </flux:navlist.item>
        <flux:navlist.item class="py-5 text-base" :href="route('staff.active.page', ['id' => $idDetail, 'status' => 'court-schedule'])" :current="isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'court-schedule' || isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'court-result'" wire:navigate>
            <div class="absolute -inset-y-[1px] w-[2px] -start-7 ms-4 {{ isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'court-schedule' || isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'court-result' ? 'bg-zinc-800 dark:bg-white' : 'bg-zinc-200 dark:bg-zinc-700' }}" ></div>
            Jadwal Sidang
        </flux:navlist.item>
        <flux:navlist.item class="py-5 text-base" :href="route('staff.active.detail-case', ['id' => $idDetail, 'status' => 'detail-case'])" :current="isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'detail-case'" wire:navigate>
            <div class="absolute -inset-y-[1px] w-[2px] -start-7 ms-4 {{ isset(request()->uri()->pathSegments()[3]) && request()->uri()->pathSegments()[3] === 'detail-case' ? 'bg-zinc-800 dark:bg-white' : 'bg-zinc-200 dark:bg-zinc-700' }}" ></div>
            Detail Kasus
        </flux:navlist.item>
    </flux:navlist.group>
</flux:navlist>
@isset($menuInfo)
    {{ $menuInfo }}
@endisset
