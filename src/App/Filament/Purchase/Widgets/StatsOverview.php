<?php

namespace App\Filament\Purchase\Widgets;

use Domain\Purchases\Models\Request;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $query = Request::query();

        return [
            Stat::make('Request Draft', $query->where('created_by', auth()->user()->id)->where('is_submited', false)->count())
                ->description('Not yet submitted')
                ->descriptionIcon('tabler-pencil')
                ->color('warning'),
            Stat::make('Request Submitted', $query->where('created_by', auth()->user()->id)->where('is_submited', true)->count())
                ->description('Already submitted')
                ->descriptionIcon('tabler-arrow-autofit-up')
                ->color('success'),

        ];
    }
}
