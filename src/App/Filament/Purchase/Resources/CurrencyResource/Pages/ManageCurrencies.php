<?php

namespace App\Filament\Purchase\Resources\CurrencyResource\Pages;

use App\Filament\Purchase\Resources\CurrencyResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCurrencies extends ManageRecords
{
    protected static string $resource = CurrencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
