<?php

namespace App\Filament\Kanban\Resources\PlanQueueResource\Pages;

use App\Filament\Kanban\Resources\PlanQueueResource;
use Domain\Kanban\Models\PlanQueue;
use Filament\Facades\Filament;
use Filament\Resources\Pages\Page;

class CardList extends Page
{
    public $records;
    protected static string $resource = PlanQueueResource::class;
    public array $expanded = [2];
    protected static string $view = 'kanban.pages.queue';
    public $headers = [
        ['key' => 'plan.sewing.display_name', 'label' => 'Sewing'],
        ['key' => 'plan_id', 'label' => 'Running'],
        ['key' => 'plan.style_id', 'label' => 'Style'],
        ['key' => 'plan.contract_id', 'label' => 'Contract'],
        ['key' => 'plan.plan_qty', 'label' => 'Quantity'],
    ];
    public function mount()
    {
        $this->records = PlanQueue::with('plan', 'plan.sewing')->whereBelongsTo(Filament::getTenant())->get();
    }
}
