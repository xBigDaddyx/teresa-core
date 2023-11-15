<?php

namespace App\Filament\Purchase\Resources\RequestResource\Pages;

use App\Filament\Purchase\Resources\RequestResource;
use Domain\Purchases\Models\Request;
use Domain\Users\Models\Company;
use Filament\Facades\Filament;
use Filament\Resources\Pages\Page;

class RequestDocument extends Page
{
    protected static string $resource = RequestResource::class;

    protected static string $view = 'filament.purchase.resources.request-resource.pages.request-document';
    public Request $record;
    public Company $company;

    public function mount($record)
    {
        $this->record = $record;
        $this->company = Filament::getTenant();
    }
}
