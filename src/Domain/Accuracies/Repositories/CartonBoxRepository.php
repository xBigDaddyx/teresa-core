<?php

namespace Domain\Accuracies\Repositories;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Domain\Accuracies\Models\CartonBox;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CartonBoxRepository
{

    public function Output(string $date, string $tenant, string $buyer = null)
    {
        if ($buyer === null || $buyer === '' || empty($buyer)) {
            return CartonBox::where('company_id', $tenant)->whereDate('completed_at', '=', $date)->count();
        }
        return CartonBox::where('company_id', $tenant)->whereHas('packingList', function (Builder $query) use ($buyer) {
            $query->where('buyer_id', $buyer);
        })->whereDate('completed_at', '=', $date)->count();
    }
}
