<?php

use App\Models\Client;
use App\Models\Lawyer;
use App\Models\LegalCase;
use Carbon\Carbon;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new
#[Title('Dashboard')]
class extends Component {

    public int $totalCases = 0;
    public int $totalLawyers = 0;
    public int $totalClients = 0;
    public int $totalActiveCases = 0;
    public $lawyerHandlingCases;
    public $monthlyCases;
    public $monthlyStatusCases;

    public function mount(): void
    {
        $this->totalCases = LegalCase::count();
        $this->totalLawyers = Lawyer::count();
        $this->totalClients = Client::count();
        $this->totalActiveCases = LegalCase::where('status', 'accepted')->count();
        $this->lawyerHandlingCases = Lawyer::where('user_id', auth()->user()->id)->get()->map(function ($lawyer) {
            $civilCases = LegalCase::where('lawyer_id', $lawyer->id)->where('type', 'civil')->count();
            $criminalCases = LegalCase::where('lawyer_id', $lawyer->id)->where('type', 'criminal')->count();

            return [
                'id' => $lawyer->id,
                'name' => $lawyer->user->name,
                'cases' => $civilCases + $criminalCases,
                'activeCases' => LegalCase::where('lawyer_id', $lawyer->id)->where('status', 'accepted')->count(),
                'closedCases' => LegalCase::where('lawyer_id', $lawyer->id)->where('status', 'closed')->count(),
                'verifiedCases' => LegalCase::where('lawyer_id', $lawyer->id)->where('status', 'verified')->count(),
                'civilTypeCases' => $civilCases,
                'criminalTypeCases' => $criminalCases,
                'categoryCases' => [$civilCases, $criminalCases],
            ];
        });


    }
}; ?>

<div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <div class="p-6 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-zinc-50 dark:bg-zinc-900">
                <flux:icon.square-activity class="h-10 w-10 text-zinc-900 dark:text-white mb-3"/>
                <div class="flex justify-between items-center">
                    <div class="space-y-1">
                        <flux:heading size="xl" level="1">Total Kasus</flux:heading>
                        <flux:text>Total keseluruhan kasus</flux:text>
                        <flux:text size="xl" variant="strong">{{ $this->lawyerHandlingCases[0]['cases'] ?? 0 }} Kasus
                        </flux:text>
                    </div>
                </div>
            </div>
            <div class="p-6 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-zinc-50 dark:bg-zinc-900">
                <flux:icon.square-play class="h-10 w-10 text-zinc-900 dark:text-white mb-3"/>
                <div class="flex justify-between items-center">
                    <div class="space-y-1">
                        <flux:heading size="xl" level="1">Kasus Berjalan</flux:heading>
                        <flux:text>Total keseluruhan kasus ditangani</flux:text>
                        <flux:text size="xl" variant="strong">{{ $this->lawyerHandlingCases[0]['activeCases'] ?? 0 }} Kasus
                        </flux:text>
                    </div>
                </div>
            </div>
            <div class="p-6 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-zinc-50 dark:bg-zinc-900">
                <flux:icon.shield-check class="h-10 w-10 text-zinc-900 dark:text-white mb-3"/>
                <div class="flex justify-between items-center">
                    <div class="space-y-1">
                        <flux:heading size="xl" level="1">Kasus Selesai</flux:heading>
                        <flux:text>Total keseluruhan kasus selesai ditangani</flux:text>
                        <flux:text size="xl" variant="strong">{{ $this->lawyerHandlingCases[0]['closedCases'] ?? 0 }}Klien
                        </flux:text>
                    </div>
                </div>
            </div>
            <div class="p-6 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-zinc-50 dark:bg-zinc-900">
                <flux:icon.files class="h-10 w-10 text-zinc-900 dark:text-white mb-3"/>
                <div class="flex justify-between items-center">
                    <div class="space-y-1">
                        <flux:heading size="xl" level="1">Menunggu Persetujuan</flux:heading>
                        <flux:text>Total kasus yang menunggu persetujuan pimpinan</flux:text>
                        <flux:text size="xl" variant="strong">{{ $this->lawyerHandlingCases[0]['verifiedCases'] ?? 0 }}
                            Kasus
                        </flux:text>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="flex justify-center items-center p-6 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-zinc-50 dark:bg-zinc-900">
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
            function getCaseTypeChart(isDark) {
                return {
                    series: @json($this->lawyerHandlingCases[0]['categoryCases'] ?? []),
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
