<?php

namespace App\Filament\Purchase\Resources\ApprovalFlowResource\Pages;

use App\Filament\Purchase\Resources\ApprovalFlowResource;
use Domain\Purchases\Models\ApprovalFlow;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;

class CreateApprovalFlow extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;
    protected static string $resource = ApprovalFlowResource::class;
    protected function getSteps(): array
    {
        return [
            Forms\Components\Wizard\Step::make('Approval Information')
                ->description('Information of approval header')
                ->icon('tabler-forms')
                ->schema([
                    Forms\Components\Grid::make(3)
                        ->schema([

                            Forms\Components\Select::make('type')
                                ->required()
                                ->hint('Select approval type')
                                ->options([
                                    'PR' => 'Purchase Request',
                                    'PO' => 'Purchase Order',
                                ])
                                ->live()
                                ->afterStateUpdated(function (Set $set, ?string $state) {

                                    $flows = ApprovalFlow::where('type', $state)->orderBy('order', 'desc')->value('order');

                                    if ((int)$flows > 0) {

                                        return $set('order', (int)$flows + 1);
                                    }


                                    return $set('order', 0);
                                })
                                ->label(__('Approval Type')),
                            Forms\Components\Select::make('level')
                                ->required()
                                ->options([
                                    'User' => 'User',
                                    'Supervisor' => 'Supervisor',
                                    'Manager' => 'Manager',
                                    'Purchasing' => 'Purchasing',
                                    'Purchasing Manager' => 'Purchasing Manager',
                                    'Finance Controller' => 'Finance Controller',
                                    'CFO' => 'CFO',
                                    'General Manager' => 'General Manager',
                                    'Country Head' => 'Country Head',
                                ])
                                ->label(__('Approval Level')),
                            Forms\Components\TextInput::make('order')
                                ->hint('Define approval order')
                                ->hidden(fn (Get $get): bool => $get('type') == null || $get('type') == '')
                                ->required()
                                ->numeric()
                                ->minValue(function (Get $get) {
                                    $type = $get('type');
                                    if ($type !== '' || $type !== null) {
                                        $queues = ApprovalFlow::where('type', $type)->orderBy('order', 'desc')->value('order');

                                        if ((int)$queues > 0) {
                                            return (int)$queues + 1;
                                        }
                                    }

                                    return 0;
                                })
                                ->maxValue(99)


                                ->label('Approval Order'),


                        ]),
                    Forms\Components\Textarea::make('description')
                        ->label('Description'),

                ]),
            Forms\Components\Wizard\Step::make('Approval Parameter')
                ->description('Information of approval parameter')
                ->icon('tabler-settings')
                ->schema([
                    Forms\Components\Grid::make(3)
                        ->schema([
                            Forms\Components\Toggle::make('is_skipable')
                                ->label(__('Skipable')),
                            Forms\Components\Repeater::make('parameter')
                                ->schema([
                                    Forms\Components\Select::make('entity')
                                        ->options([
                                            'Price' => 'Price',
                                            'Capex Number' => 'Capex Number',
                                        ]),
                                    Forms\Components\Select::make('operator')
                                        ->label(__('Operator'))
                                        ->options([
                                            '=' => '=',
                                            '!=' => '!=',
                                            '<' => '<',
                                            '<=' => '<=',
                                            '>' => '>',
                                            '>=' => '>=',
                                            'contains' => 'contains',
                                            'is null' => 'is null',
                                            'is not null' => 'is not null',
                                            'is empty' => 'is empty',
                                            'is not empty' => 'is not empty',
                                            'is between' => 'is between',
                                            'is not between' => 'is not between',


                                        ]),
                                    Forms\Components\TextInput::make('value')
                                        ->label(__('Value'))
                                ])
                                ->columns(2)
                                ->columnSpanFull()
                        ]),
                ]),


        ];
    }
}
