@props(['active', 'profile' => null,'action' => null, 'tabUrl' => null, 'menu' => null, 'back' => null, 'position' => 'left', 'menuInfo' => null, 'idDetail' => null])
<div class="flex max-md:flex-col items-start {{ $position == 'right' ? 'flex-row-reverse' : '' }}">
    <div class="w-full md:w-[250px] mx-2 space-y-2">
        @isset($profile)
            {{ $profile }}
        @endisset
        @switch($menu)
            @case('master-data')
                <x-partials.menu-sidebar.master-data :menu-info="$menuInfo" />
                @break
            @case('case')
                <x-partials.menu-sidebar.case :menu-info="$menuInfo" />
                @break
            @case('staff-case')
                <x-partials.menu-sidebar.staff-case :menu-info="$menuInfo" />
                @break
            @case('leader-case')
                <x-partials.menu-sidebar.leader-case :menu-info="$menuInfo" />
                @break
            @case('staff-active-case')
                <x-partials.menu-sidebar.staff-active-case :id-detail="$idDetail" :menu-info="$menuInfo" />
                @break
            @case('lawyer-active-case')
                <x-partials.menu-sidebar.lawyer-active-case :id-detail="$idDetail" :menu-info="$menuInfo" />
                @break
            @case('setting')
                <x-partials.menu-sidebar.setting />
                @break
            @default
        @endswitch
        @isset($tabUrl)
            <x-tab-url :data-url="$tabUrl"  />
        @endisset
    </div>
    <flux:separator class="md:hidden" />
    <div class="flex-1 max-md:pt-6 self-stretch">
        <x-partials.breadcrumbs :active="$active" :action="$action" :back="$back"/>
        <div class="mt-1">
            {{ $slot }}
        </div>
    </div>
</div>
