<?php

namespace App\Filament\Kanban\Resources\PlanResource\Pages;

use App\Filament\Kanban\Resources\PlanResource;
use Domain\Accuracies\Models\Buyer;
use Domain\Kanban\Models\Sewing;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Resources\Pages\CreateRecord;

class CreatePlan extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;
    protected static string $resource = PlanResource::class;
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
                ->description('Select which sewing for this production plan.')
                ->schema([
                    Forms\Components\Grid::make(1)
                        ->schema([
                            Forms\Components\Select::make('sewing_id')
                                ->label('Sewing')
                                ->options(Sewing::whereBelongsTo(Filament::getTenant())->pluck('id', 'id'))
                                ->required(),
                        ])

                ]),
            Forms\Components\Wizard\Step::make('Buyer')
                ->description('Add buyer information for this plan.')
                ->schema([
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\Select::make('buyer')
                                ->columnSpan(2)
                                ->label('Buyer')
                                ->options(Buyer::whereBelongsTo(Filament::getTenant())->pluck('name', 'id'))
                                ->required(),
                            Forms\Components\TextInput::make('contract_id')
                                ->label('Contract')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('style_id')
                                ->label('Style')
                                ->required()
                                ->maxLength(255),


                        ])
                ]),
            Forms\Components\Wizard\Step::make('Quantity and Date')
                ->description('Add information about quantity and start, end, exit factory date for this plan.')
                ->schema([
                    Forms\Components\Grid::make(3)
                        ->schema([
                            Forms\Components\TextInput::make('plan_qty')
                                ->numeric()
                                ->label('Plan Quantity')
                                ->required(),
                            Forms\Components\DatePicker::make('sewing_start_date')
                                ->label('Sewing Start')
                                ->required(),
                            Forms\Components\DatePicker::make('sewing_end_date')
                                ->label('Sewing End')
                                ->required(),
                            Forms\Components\DatePicker::make('exit_fty_date')
                                ->label('Exit Factory')
                                ->required(),

                        ])
                ]),
        ];
    }
}
