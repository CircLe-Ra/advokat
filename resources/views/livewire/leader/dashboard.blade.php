<?php

use App\Models\Client;
use App\Models\Lawyer;
use App\Models\LegalCase;
use Carbon\Carbon;
use Livewire\Volt\Component;

new class extends Component {

    public int $totalCases = 0;
    public int $totalLawyers = 0;
    public int $totalClients = 0;
    public int $totalActiveCases = 0;
    public $lawyerHandlingCases;
    public $monthlyCases;
    public $monthlyStatusCases;
    public $caseStatusByType;

    public function mount(): void
    {
        $this->totalCases = LegalCase::count();
        $this->totalLawyers = Lawyer::count();
        $this->totalClients = Client::count();
        $this->totalActiveCases = LegalCase::where('status', 'accepted')->count();
        $this->lawyerHandlingCases = Lawyer::all()->map(function ($lawyer) {
            return [
                'id' => $lawyer->id,
                'name' => $lawyer->user->name,
                'cases' => LegalCase::where('lawyer_id', $lawyer->id)->count()
            ];
        });


        $year = Carbon::now()->year;

        $casesPerMonth = DB::table('legal_cases')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month')
            ->toArray();

        for ($i = 1; $i <= 12; $i++) {
            $this->monthlyCases[] = $casesPerMonth[$i] ?? 0;
        }

        $raw = DB::table('legal_cases')
            ->selectRaw('MONTH(created_at) as month, status, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'), 'status')
            ->get();

        $statuses = $raw->pluck('status')->unique()->toArray();
        $cases = [];

        foreach ($statuses as $status) {
            $cases[$status] = array_fill(1, 12, 0);
        }

        foreach ($raw as $row) {
            $cases[$row->status][$row->month] = $row->total;
        }

        $this->monthlyStatusCases = collect($statuses)->map(function($status) use ($cases) {
            return [
                'name' => ucfirst(__($status)),
                'data' => array_values($cases[$status]),
            ];
        })->toArray();


        $civilData = \DB::table('legal_cases')
            ->where('type', 'civil')
            ->select(\DB::raw('status, count(*) as count'))
            ->groupBy('status')
            ->get();

        $criminalData = \DB::table('legal_cases')
            ->where('type', 'criminal')
            ->select(\DB::raw('status, count(*) as count'))
            ->groupBy('status')
            ->get();

        $statuses = ['draft', 'pending', 'verified', 'rejected', 'accepted', 'closed'];

        $civilCounts = [];
        $criminalCounts = [];

        foreach ($statuses as $status) {
            $civilCounts[] = $civilData->where('status', $status)->first()->count ?? 0;
            $criminalCounts[] = $criminalData->where('status', $status)->first()->count ?? 0;
        }

        $this->caseStatusByType = [
            [
                'name' => 'Perdata',
                'data' => $civilCounts
            ],
            [
                'name' => 'Pidana',
                'data' => $criminalCounts
            ]
        ];

    }
}; ?>

<div>
    <flux:callout wire:poll.visible class="mb-3 py-2.5 border border-zinc-100 bg-zinc-100 shadow-md dark:border-zinc-700 !rounded-lg dark:bg-zinc-900" icon="clock" variant="secondary" inline>
        <div class="flex justify-between items-center">
            <flux:callout.heading>{{ Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y - HH:mm:ss') }} WIT</flux:callout.heading>
            <flux:callout.heading>Selamat Datang, {{ auth()->user()->name }}</flux:callout.heading>
        </div>
    </flux:callout>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-1">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="p-6 border border-zinc-100 dark:border-zinc-700 rounded-lg bg-zinc-100 shadow-md dark:bg-zinc-900">
                <flux:icon.square-activity class="h-10 w-10 text-zinc-900 dark:text-white"/>
                <div class="flex justify-between items-center">
                    <div class="space-y-1">
                        <flux:heading size="xl" level="1">Total Kasus</flux:heading>
                        <flux:text>Total keseluruhan kasus</flux:text>
                        <flux:text size="xl" variant="strong">{{ $this->totalCases ?? 0 }} Kasus</flux:text>
                    </div>
                </div>
            </div>
            <div class="p-6 border border-zinc-100 dark:border-zinc-700 rounded-lg bg-zinc-100 shadow-md dark:bg-zinc-900">
                <flux:icon.users class="h-10 w-10 text-zinc-900 dark:text-white"/>
                <div class="flex justify-between items-center">
                    <div class="space-y-1">
                        <flux:heading size="xl" level="1">Total Pengacara</flux:heading>
                        <flux:text>Total keseluruhan pengacara</flux:text>
                        <flux:text size="xl" variant="strong">{{ $this->totalLawyers ?? 0 }} Pengacara</flux:text>
                    </div>
                </div>
            </div>
            <div class="p-6 border border-zinc-100 dark:border-zinc-700 rounded-lg bg-zinc-100 shadow-md dark:bg-zinc-900">
                <flux:icon.user-group class="h-10 w-10 text-zinc-900 dark:text-white"/>
                <div class="flex justify-between items-center">
                    <div class="space-y-1">
                        <flux:heading size="xl" level="1">Total Klien</flux:heading>
                        <flux:text>Total keseluruhan klien terdaftar</flux:text>
                        <flux:text size="xl" variant="strong">{{ $this->totalClients ?? 0 }} Klien</flux:text>
                    </div>
                </div>
            </div>
            <div class="p-6 border border-zinc-100 dark:border-zinc-700 rounded-lg bg-zinc-100 shadow-md dark:bg-zinc-900">
                <flux:icon.briefcase class="h-10 w-10 text-zinc-900 dark:text-white"/>
                <div class="flex justify-between items-center">
                    <div class="space-y-1">
                        <flux:heading size="xl" level="1">Total Kasus Berjalan</flux:heading>
                        <flux:text>Total kasus ditangani saat ini</flux:text>
                        <flux:text size="xl" variant="strong">{{ $this->totalActiveCases ?? 0 }} Kasus</flux:text>
                    </div>
                </div>
            </div>
        </div>
        <div wire:ignore class="p-6 border border-zinc-100 dark:border-zinc-700 rounded-lg bg-zinc-100 shadow-md dark:bg-zinc-900">
            <div id="chart_case"></div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-1">
        @foreach($this->lawyerHandlingCases as $lawyer)
            <div class="mt-2 p-6 border border-zinc-100 dark:border-zinc-700 rounded-lg bg-zinc-100 shadow-md dark:bg-zinc-900">
                <div class="flex justify-between items-center">
                    <div class="space-y-1">
                        <flux:heading size="xl" level="1">{{ $lawyer['name'] }}</flux:heading>
                        <flux:text>Total kasus ditangani</flux:text>
                        <flux:text size="xl" variant="strong">{{ $lawyer['cases'] }} Kasus</flux:text>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div wire:ignore class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <div class="col-span-2 mt-2 mb-6 p-6 border border-zinc-100 dark:border-zinc-700 rounded-lg bg-zinc-100 shadow-md dark:bg-zinc-900">
            <div id="chart_case_status"></div>
        </div>
        <div class="mt-2 mb-6 p-6 border border-zinc-100 dark:border-zinc-700 rounded-lg bg-zinc-100 shadow-md dark:bg-zinc-900">
            <div id="chart_case_by_type"></div>
        </div>
    </div>
</div>

@pushonce('scripts')
    @script
    <script>
        const monthlyCases = @json($this->monthlyCases);
        const monthlyStatusCases = @json($this->monthlyStatusCases);

        document.addEventListener('livewire:navigated', () => {
            function getCaseChart(isDark) {
                return {
                    series: [{
                        name: 'Kasus',
                        data: monthlyCases
                    }],
                    chart: {
                        type: 'area',
                        stacked: false,
                        height: 350,
                        background: 'transparent',
                        foreColor: isDark ? '#fff' : '#333',
                        zoom: {
                            type: 'x',
                            enabled: true,
                            autoScaleYaxis: true
                        },
                        toolbar: {
                            autoSelected: 'zoom'
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    markers: {
                        size: 0,
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            inverseColors: false,
                            opacityFrom: 0.5,
                            opacityTo: 0,
                            stops: [0, 90, 100]
                        },
                    },
                    yaxis: {
                        labels: {
                            formatter: function (val) {
                                return val;
                            },
                        },
                        title: {
                            text: 'Jumlah Kasus',
                            style: {
                                color: isDark ? '#fff' : '#000'
                            }
                        }
                    },
                    xaxis: {
                        categories: [
                            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
                        ],
                        title: {
                            text: 'Bulan',
                            style: {
                                color: isDark ? '#fff' : '#000'
                            }
                        }
                    },
                    title: {
                        text: 'Jumlah Kasus per Bulan - {{ now()->year }}',
                        align: 'center',
                        style: {
                            color: isDark ? '#fff' : '#000'
                        }
                    },
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

            function getCaseChartStatus(isDark) {
                return {
                    series: monthlyStatusCases,
                    chart: {
                        type: 'bar',
                        height: 350,
                        stacked: true,
                        background: 'transparent',
                        foreColor: isDark ? '#fff' : '#333',
                        zoom: { enabled: true },
                    },
                    title: {
                        text: 'Jumlah Kasus berdasarkan Status per Bulan - {{ now()->year }}',
                        align: 'center',
                        style: {
                            color: isDark ? '#fff' : '#000'
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            borderRadius: 4,
                            borderRadiusWhenStacked: 'last',
                            dataLabels: {
                                total: {
                                    enabled: true,
                                    formatter: val => val,
                                    style: {
                                        fontSize: '13px',
                                        fontWeight: 900,
                                        color: isDark ? '#fff' : '#333'
                                    }
                                }
                            }
                        }
                    },
                    xaxis: {
                        categories: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
                        title: {
                            text: 'Bulan',
                            style: {
                                color: isDark ? '#fff' : '#000'
                            }
                        },
                    },
                    yaxis: {
                        title: {
                            text: 'Jumlah Kasus',
                            style: {
                                color: isDark ? '#fff' : '#000'
                            }
                        }
                    },
                    legend: {
                        position: 'right',
                        offsetY: 0
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: val => val + " Kasus",
                    },
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

            function getTotalCaseByType(isDark) {
                return {
                    series: @json($this->caseStatusByType),
                    chart: {
                        height: 350,
                        type: 'radar',
                        background: 'transparent',
                        foreColor: isDark ? '#fff' : '#333',
                        zoom: { enabled: true },
                        toolbar: {
                            show: false
                        },
                        dropShadow: {
                            enabled: true,
                            blur: 1,
                            left: 1,
                            top: 1
                        }
                    },
                    title: {
                        text: 'Jumlah Kasus berdasarkan Jenis - {{ now()->year }}',
                        align: 'center',
                        style: {
                            color: isDark ? '#fff' : '#000'
                        }
                    },
                    stroke: {
                        width: 2
                    },
                    fill: {
                        opacity: 0.1
                    },
                    markers: {
                        size: 0
                    },
                    yaxis: {
                        stepSize: 10
                    },
                    xaxis: {
                        categories: ['Draf','Menunggu','Diverifikasi','Ditolak','Diterima', 'Ditutup'],
                        title: {
                            text: 'Jumlah Kasus',
                            style: {
                                color: isDark ? '#fff' : '#000'
                            }
                        }
                    }
                }
            }

            let caseChart, caseChartStatus, caseChartByType;
            function renderCharts() {
                if (caseChart) caseChart.destroy();
                if (caseChartStatus) caseChartStatus.destroy();
                if (caseChartByType) caseChartByType.destroy();

                if (document.querySelector("#chart_case")) {
                    caseChart = new ApexCharts(document.querySelector("#chart_case"), getCaseChart($flux.dark));
                    caseChart.render();
                }
                if (document.querySelector("#chart_case_status")) {
                    caseChartStatus = new ApexCharts(document.querySelector("#chart_case_status"), getCaseChartStatus($flux.dark));
                    caseChartStatus.render();
                }

                if (document.querySelector("#chart_case_by_type")) {
                    caseChartByType = new ApexCharts(document.querySelector("#chart_case_by_type"), getTotalCaseByType($flux.dark));
                    caseChartByType.render();
                }
            }

            renderCharts();

            $watch('$flux.dark', value => {
                if (typeof caseChart !== 'undefined') {
                    caseChart.destroy();
                }
                renderCharts();
            });
        }, { once: true });
    </script>
    @endscript
@endpushonce
