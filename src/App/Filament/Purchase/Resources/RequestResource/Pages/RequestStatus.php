<?php

namespace App\Filament\Purchase\Resources\RequestResource\Pages;

use App\Filament\Purchase\Resources\RequestResource;
use Filament\Resources\Pages\Page;

class RequestStatus extends Page
{
    protected static string $resource = RequestResource::class;

    protected static string $view = 'filament.purchase.resources.request-resource.pages.request-status';
}
