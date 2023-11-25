<?php

namespace App\Filament\Purchase\Resources\ApprovalFlowResource\Pages;

use App\Filament\Purchase\Resources\ApprovalFlowResource;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;
use Xbigdaddyx\HarmonyFlow\Models\Flow;

class CreateApprovalFlow extends CreateRecord
{
    // use CreateRecord\Concerns\HasWizard;
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

                                    $flows = Flow::where('type', $state)->orderBy('order', 'desc')->value('order');

                                    if ((int)$flows > 0) {

                                        return $set('order', (int)$flows + 1);
                                    }


                                    return $set('order', 0);
                                })
                                ->label(__('Approval Type')),
                            Forms\Components\Select::make('designation_id')
                                ->required()
                                ->relationship('designation', 'name')
                                ->label(__('Approval Designation')),
                            Forms\Components\TextInput::make('order')
                                ->hint('Define approval order')
                                ->hidden(fn (Get $get): bool => $get('type') == null || $get('type') == '')
                                ->required()
                                ->numeric()
                                ->minValue(function (Get $get) {
                                    $type = $get('type');
                                    if ($type !== '' || $type !== null) {
                                        $queues = Flow::where('type', $type)->orderBy('order', 'desc')->value('order');

                                        if ((int)$queues > 0) {
                                            return (int)$queues + 1;
                                        }
                                    }

                                    return 1;
                                })
                                ->maxValue(99)


                                ->label('Approval Order'),


                        ]),


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
    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     dd($data);
    //     return $data;
    // }
}
