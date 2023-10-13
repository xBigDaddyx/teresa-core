<?php

namespace App\Filament\Accuracy\Widgets;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Domain\Accuracies\Repositories\CartonBoxRepository;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Collection;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ValidationOutputChart extends ApexChartWidget
{
    protected int | string | array $columnSpan = 'full';
    /**
     * Chart Id
     *
     * @var string
     */
    protected static string $chartId = 'validationOutputChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Carton Box Validation Output';

    protected function getFormSchema(): array
    {
        return [

            DatePicker::make('period_start')
                ->default(Carbon::now()->subDays(14)),

            DatePicker::make('period_end')
                ->default(Carbon::now()),

        ];
    }
    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $cartonBoxRepository = new CartonBoxRepository();
        $period = CarbonPeriod::create($this->filterFormData['period_start'], $this->filterFormData['period_end']);
        $dates = new Collection();
        $series1 = new Collection();
        foreach ($period as $key => $date) {
            $dates->push(Carbon::parse($date)->format('d M'));
            $series1->push((string)$cartonBoxRepository->Output($date, Filament::getTenant()->id));
        }

        return [

            'chart' => [
                'type' => 'line',
                'height' => 300,
                'toolbar' => [
                    'show' => false,
                ],
            ],
            'series' => [
                [
                    'name' => 'Outputs',
                    'data' => $series1->toArray(),
                    'type' => 'column',
                ],
                // [
                //     'name' => 'Variances',
                //     'data' => $series2,
                //     'type' => 'line',
                // ],
            ],
            'stroke' => [
                'width' => [0, 4],
                'curve' => 'smooth',
            ],
            'xaxis' => [
                'categories' => $dates->toArray(),
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'legend' => [
                'labels' => [
                    'colors' => '#9ca3af',
                    'fontWeight' => 600,
                ],
            ],
            'colors' => ['#F29727', '#2B2730'],
            'fill' => [
                'type' => 'gradient',
                'gradient' => [
                    'shade' => 'dark',
                    'type' => 'vertical',
                    'shadeIntensity' => 0.5,
                    'gradientToColors' => ['#F2BE22'],
                    'inverseColors' => true,
                    'opacityFrom' => 1,
                    'opacityTo' => 1,
                    'stops' => [0, 100],
                ],
            ],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 10,
                ],
            ],
        ];
    }
}
