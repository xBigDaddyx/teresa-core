<?php

namespace App\Filament\Accuracy\Resources\BuyerResource\Pages;

use App\Filament\Accuracy\Resources\BuyerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms;
use Filament\Forms\Get;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CreateBuyer extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
    protected static string $resource = BuyerResource::class;
}
