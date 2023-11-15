<?php

namespace App\Filament\Purchase\Resources\OrderResource\Pages;

use App\Filament\Purchase\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
}
