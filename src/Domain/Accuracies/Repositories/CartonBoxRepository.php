<?php

namespace Domain\Accuracies\Repositories;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Domain\Accuracies\Models\CartonBox;
use Illuminate\Database\Eloquent\Collection;

class CartonBoxRepository
{

    public function Output(string $date, string $tenant)
    {
        return CartonBox::where('company_id', $tenant)->whereDate('completed_at', '=', $date)->count();
    }
}
