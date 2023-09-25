<?php

namespace App\Filament\Widgets;

use Domain\Users\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Collection;

class UserDepartmentsChart extends ChartWidget
{
    protected static ?string $heading = 'Departments';

    protected function getData(): array
    {
        $depts = User::where('current_company_id', auth()->user()->current_company_id)->select('department')->distinct()->get();
        $data = new Collection();
        $labels = new Collection();
        foreach ($depts as $dept) {
            $labels->push(
                $dept->department,

            );
            $data->push(User::where('department', $dept->department)->count());
        }

        return [
            'datasets' => [
                [
                    'label' => 'Users',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];

    }

    protected function getType(): string
    {
        return 'line';
    }
}
