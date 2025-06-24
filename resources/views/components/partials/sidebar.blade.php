@props(['active', 'action' => null, 'tabUrl' => null, 'menu' => null, 'back' => null, 'position' => 'left'])
<div class="flex max-md:flex-col items-start {{ $position == 'right' ? 'flex-row-reverse' : '' }}">
    <div class="w-full md:w-[250px] mx-2">
        @switch($menu)
            @case('case')
                <x-partials.menu-sidebar.case />
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
