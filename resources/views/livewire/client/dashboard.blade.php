<?php

use App\Models\Client;
use App\Models\Lawyer;
use App\Models\LegalCase;
use App\Models\MeetingSchedule;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new
#[Title('Dashboard')]
class extends Component {

    public int $totalCases = 0;
    public int $totalLawyers = 0;
    public int $totalClients = 0;
    public int $totalActiveCases = 0;
    public $clientHandlingCases;
    public $monthlyCases;
    public $monthlyStatusCases;

    public function mount(): void
    {
        $this->totalCases = LegalCase::count();
        $this->totalLawyers = Lawyer::count();
        $this->totalClients = Client::count();
        $this->totalActiveCases = LegalCase::where('status', 'accepted')->count();
        $this->clientHandlingCases = Client::where('user_id', auth()->user()->id)->get()->map(function ($client) {
            $civilCases = LegalCase::where('client_id', $client->id)->where('type', 'civil')->count();
            $criminalCases = LegalCase::where('client_id', $client->id)->where('type', 'criminal')->count();

            return [
                'id' => $client->id,
                'name' => $client->user->name,
                'cases' => $civilCases + $criminalCases,
                'activeCases' => LegalCase::where('client_id', $client->id)->where('status', 'accepted')->count(),
                'closedCases' => LegalCase::where('client_id', $client->id)->where('status', 'closed')->count(),
                'verifiedCases' => LegalCase::where('client_id', $client->id)->where('status', 'verified')->count(),
                'civilTypeCases' => $civilCases,
                'criminalTypeCases' => $criminalCases,
                'categoryCases' => [$civilCases, $criminalCases],
                'schedules' => MeetingSchedule::whereHas('legalCase', function ($query) use ($client) {
                    $query->where('client_id', $client->id);
                })->get(),
            ];
        });
    }

    public function __getCaseNumber($id)
    {
        $case = LegalCase::find($id);
        return $case->number;
    }

}; ?>

<div>
    <flux:callout wire:poll.visible class="mb-3 py-2.5 border border-zinc-100 bg-zinc-100 shadow-md dark:border-zinc-700 !rounded-lg dark:bg-zinc-900" icon="clock" variant="secondary" inline>
        <div class="flex justify-between items-center">
            <flux:callout.heading>{{ Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y - HH:mm:ss') }} WIT</flux:callout.heading>
            <flux:callout.heading>Selamat Datang, {{ auth()->user()->name }}</flux:callout.heading>
        </div>
    </flux:callout>
    <div wire:ignore class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="p-6 border border-zinc-100 bg-zinc-100 shadow-md dark:border-zinc-700 rounded-lg dark:bg-zinc-900">
                <flux:icon.square-activity class="h-10 w-10 text-zinc-900 dark:text-white mb-3"/>
                <div class="flex justify-between items-center">
                    <div class="space-y-1">
                        <flux:heading size="xl" level="1">Total Kasus</flux:heading>
                        <flux:text>Total keseluruhan kasus yang anda ajukan</flux:text>
                        <flux:text size="xl" variant="strong">{{ $this->clientHandlingCases[0]['cases'] }} Kasus
                        </flux:text>
                    </div>
                </div>
            </div>
            <div class="p-6 border border-zinc-100 bg-zinc-100 shadow-md dark:border-zinc-700 rounded-lg dark:bg-zinc-900">
                <flux:icon.square-play class="h-10 w-10 text-zinc-900 dark:text-white mb-3"/>
                <div class="flex justify-between items-center">
                    <div class="space-y-1">
                        <flux:heading size="xl" level="1">Kasus Berjalan</flux:heading>
                        <flux:text>Total keseluruhan kasus anda yang ditangani</flux:text>
                        <flux:text size="xl" variant="strong">{{ $this->clientHandlingCases[0]['activeCases'] }} Kasus
                        </flux:text>
                    </div>
                </div>
            </div>
            <div class="p-6 border border-zinc-100 bg-zinc-100 shadow-md dark:border-zinc-700 rounded-lg dark:bg-zinc-900">
                <flux:icon.shield-check class="h-10 w-10 text-zinc-900 dark:text-white mb-3"/>
                <div class="flex justify-between items-center">
                    <div class="space-y-1">
                        <flux:heading size="xl" level="1">Kasus Selesai</flux:heading>
                        <flux:text>Total keseluruhan kasus selesai ditangani</flux:text>
                        <flux:text size="xl" variant="strong">{{ $this->clientHandlingCases[0]['closedCases'] }} Klien
                        </flux:text>
                    </div>
                </div>
            </div>
            <div class="p-6 border border-zinc-100 bg-zinc-100 shadow-md dark:border-zinc-700 rounded-lg dark:bg-zinc-900">
                <flux:icon.files class="h-10 w-10 text-zinc-900 dark:text-white mb-3"/>
                <div class="flex justify-between items-center">
                    <div class="space-y-1">
                        <flux:heading size="xl" level="1">Menunggu Persetujuan</flux:heading>
                        <flux:text>Total kasus yang menunggu persetujuan pimpinan</flux:text>
                        <flux:text size="xl" variant="strong">
                            {{ $this->clientHandlingCases[0]['verifiedCases'] }} Kasus
                        </flux:text>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-center items-center p-6 border border-zinc-100 bg-zinc-100 shadow-md dark:border-zinc-700 rounded-lg dark:bg-zinc-900">
            <div id="chart_case_by_type"></div>
        </div>
    </div>
    <x-table thead="#, No. Kasus, Tentang, Waktu, Status," :action="false" label="Agenda Pertemuan"
             sub-label="Jadwal pertemuan dengan pengacara">
        @if($this->clientHandlingCases[0]['schedules']->count())
            @foreach($this->clientHandlingCases[0]['schedules'] as $schedule)
                <tr class="bg-white border-b dark:bg-zinc-800 dark:border-zinc-700 border-zinc-200 ">
                    <th scope="col" class="px-6 py-3">
                        {{ $loop->iteration }}
                    </th>
                    <td class="px-6 py-4 text-nowrap">
                        {{ $this->__getCaseNumber($schedule->legal_case_id) }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $schedule->about }}
                    </td>
                    <td class="px-6 py-4 text-nowrap">
                        {{ Carbon::parse($schedule->date_time)->isoFormat('dddd, D MMMM Y HH:mm') }} WIT
                    </td>
                    <td class="px-6 py-4">
                        {{ $schedule->status == 'pending' ? 'Belum Terlaksana' : ($schedule->status == 'finished' ? 'Terlaksana' : 'Dibatalkan') }}
                    </td>
                    <td class="px-6 py-4">
                        <flux:dropdown position="bottom" align="center">
                            <flux:button size="sm" icon:trailing="chevron-down" variant="filled">Aksi</flux:button>
                            <flux:menu>
                                <flux:menu.group heading="Hasil Pertemuan">
                                    <flux:menu.item icon:variant="micro" icon:trailing="arrow-up-right"
                                                    icon="file-plus-2"
                                                    href="{{ route('client.case.handling', ['case' => $schedule->id, 'status' => 'meeting-result']) }}"
                                                    wire:navigate>
                                        Hasil Pertemuan
                                    </flux:menu.item>
                                    <flux:menu.item icon:variant="micro" icon:trailing="arrow-up-right" icon="photo"
                                                    href="{{ route('client.case.handling', ['case' => $schedule->id, 'status' => 'meeting-documentation']) }}"
                                                    wire:navigate>
                                        Dokumentasi
                                    </flux:menu.item>
                                </flux:menu.group>
                            </flux:menu>
                        </flux:dropdown>
                    </td>
                </tr>
            @endforeach
        @else
            <tr class="bg-white border-b dark:bg-zinc-800 dark:border-zinc-700 border-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-600">
                <td colspan="7" class="px-6 py-4 text-center">
                    Tidak ada data
                </td>
            </tr>
        @endif
    </x-table>
</div>

@pushonce('scripts')
    @script
    <script>
        const monthlyCases = @json($this->monthlyCases);
        const monthlyStatusCases = @json($this->monthlyStatusCases);

        document.addEventListener('livewire:navigated', () => {
            function getCaseTypeChart(isDark) {
                return {
                    series: @json($this->clientHandlingCases[0]['categoryCases']),
                    chart: {
                        width: 380,
                        type: 'pie',
                    },
                    colors: ['#3b82f6', '#ef4444'],
                    legend: {
                        position: 'bottom',
                        labels: {
                            colors: isDark ? '#fff' : '#000'
                        }
                    },
                    title: {
                        text: 'Jumlah Kasus berdasarkan Jenis',
                        align: 'center',
                        style: {
                            color: isDark ? '#fff' : '#000'
                        }
                    },
                    labels: ['Perdata', 'Pidana'],
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 200
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }],
                    tooltip: {
                        shared: false,
                        theme: isDark ? 'dark' : 'light',
                        y: {
                            formatter: function (val) {
                                return val + " Kasus";
                            }
                        }
                    }
                }
            }

            let caseChart, typeChart;

            function renderCharts() {
                if (typeChart) typeChart.destroy();

                if (document.querySelector("#chart_case_by_type")) {
                    typeChart = new ApexCharts(document.querySelector("#chart_case_by_type"), getCaseTypeChart($flux.dark));
                    typeChart.render();
                }
            }

            renderCharts();

            $watch('$flux.dark', value => {
                if (typeof caseChart !== 'undefined') {
                    typeChart.destroy();
                }
                renderCharts();
            });
        }, {once: true});
    </script>
    @endscript
@endpushonce
