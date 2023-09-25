<?php

namespace App\Filament\Widgets;

use Domain\Users\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UsersOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $users = User::latest();

        return [
            Stat::make('Total users', $users->count())
                ->description('Users')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
            Stat::make('Verified users', $users->where('email_verified_at', '!=', null)->count('id'))
                ->description('Users')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

        ];
    }
}
