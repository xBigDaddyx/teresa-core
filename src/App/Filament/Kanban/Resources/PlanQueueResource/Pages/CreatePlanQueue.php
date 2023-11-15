<?php

namespace App\Filament\Kanban\Resources\PlanResource\Pages;

use App\Filament\Kanban\Resources\PlanQueueResource;
use Domain\Accuracies\Models\Buyer;
use Domain\Kanban\Models\PlanQueue;
use Domain\Kanban\Models\Sewing;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CreatePlanQueue extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;
    protected static string $resource = PlanQueueResource::class;
    public function hasSkippableSteps(): bool
    {
        return true;
    }
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
    protected function getSteps(): array
    {
        return [
            Forms\Components\Wizard\Step::make('Sewing')
                ->description('Select which sewing for this plan queue.')
                ->schema([
                    Forms\Components\Grid::make(1)
                        ->schema([
                            Forms\Components\Select::make('sewing_id')
                                ->hint('Select sewing line')
                                ->live()
                                ->options(Sewing::whereBelongsTo(Filament::getTenant())->pluck('id', 'id'))
                                ->label('Sewing'),
                            Forms\Components\Select::make('plan_id')
                                ->hint('Select sewing plan')
                                ->relationship('plan', 'contract_id', fn (Builder $query, Get $get) => $query->where('sewing_id', $get('sewing_id')))
                                ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->sewing_id} | Contract {$record->contract_id}| Style {$record->style_id} | Qty {$record->plan_qty} | Start {$record->sewing_start_date} | End {$record->sewing_end_date}")
                                ->label('Plan')
                                ->required(),
                        ])

                ]),
            Forms\Components\Wizard\Step::make('Status & Order')
                ->description('Define status and order for this plan queue.')
                ->schema([
                    Forms\Components\Radio::make('status')
                        ->hint('Select status for this queue')

                        ->options(function (Get $get) {
                            $sewing = $get('sewing_id');
                            if ($sewing) {
                                $queues = PlanQueue::whereHas('plan', function (Builder $query) use ($sewing) {
                                    $query->where('sewing_id', $sewing);
                                })->where('status', 'Active')->orWhere('status', 'Delayed')->orderBy('queue_order', 'desc')->get();

                                if ($queues->count() > 0) {
                                    return [
                                        'Ongoing' => 'Ongoing',
                                    ];
                                }
                            }

                            return [
                                'Active' => 'Active',
                                'Ongoing' => 'Ongoing',
                            ];
                        })
                        ->descriptions([
                            'Active' => 'This queue will calculate as active plan',
                            'Ongoing' => 'This queue will set as ongoing after active or delayed queue',

                        ])
                        ->default('Ongoing')
                        ->required(),
                    Forms\Components\TextInput::make('queue_order')
                        ->hint('Define queue order')
                        ->hidden(fn (Get $get): bool => $get('sewing_id') == null || $get('sewing_id') == '')
                        ->required()
                        ->minValue(function (Get $get) {
                            $sewing = $get('sewing_id');
                            if ($sewing) {
                                $queues = PlanQueue::whereHas('plan', function (Builder $query) use ($sewing) {
                                    $query->where('sewing_id', $sewing);
                                })->orderBy('queue_order', 'desc')->value('queue_order');

                                if ((int)$queues > 0) {
                                    return (int)$queues + 1;
                                }
                            }

                            return 0;
                        })

                        ->numeric()
                        ->label('Queue Order'),
                ]),

        ];
    }
}
