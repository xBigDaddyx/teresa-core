<?php

namespace App\Filament\Purchase\Resources\ProductResource\Pages;

use App\Filament\Purchase\Resources\ProductResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;
    protected static string $resource = ProductResource::class;


    protected function getSteps(): array
    {
        return [
            Forms\Components\Wizard\Step::make('Product')
                ->description('Create product form')
                ->icon('tabler-paper-bag')
                ->schema([
                    Forms\Components\Grid::make(3)
                        ->schema([
                            Forms\Components\Select::make('product_category_id')
                                ->relationship('category', 'name')
                                ->preload()
                                ->required()
                                ->label(__('Group')),
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->label(__('Name')),
                            Forms\Components\Select::make('unit_id')
                                ->relationship('unit', 'name')
                                ->preload()
                                ->required()
                                ->label(__('Unit')),


                        ])

                ]),
            Forms\Components\Wizard\Step::make('Specifications')
                ->description('Product specification details')
                ->icon('tabler-clipboard')
                ->schema([
                    Forms\Components\Grid::make(3)
                        ->schema([
                            Forms\Components\Repeater::make('specification')
                                ->label('Product Specifications')
                                ->itemLabel(fn (array $state): ?string => $state['category'] . ' : ' . $state['value'] ?? null)
                                ->schema([
                                    Forms\Components\Select::make('category')
                                        ->label(__('Category'))
                                        ->options([
                                            'Model' => 'Model',
                                            'Type' => 'Type',
                                            'Brand' => 'Brand',
                                            'Color' => 'Color',
                                            'Size' => 'Size',
                                            'Length' => 'Length',
                                            'Weight' => 'Weight',
                                            'Width' => 'Width',
                                            'Height' => 'Height',
                                            'Volume' => 'Volume',
                                            'Watt' => 'Watt',
                                            'Voltage' => 'Voltage',
                                            'Part Number' => 'Part Number',
                                        ])
                                        ->required(),
                                    Forms\Components\TextInput::make('value')
                                        ->label(__('Value'))
                                        ->required(),

                                ])
                                ->columns(2)
                                ->collapsible()
                                ->columnSpanFull()
                        ]),
                    Forms\Components\Textarea::make('remark')
                        ->label(__('Remark')),

                ]),
            Forms\Components\Wizard\Step::make('Photos')
                ->description('Product photos')
                ->icon('tabler-camera')
                ->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('photo')
                        ->collection('products')->columnSpanFull()->multiple(),
                ])

        ];
    }
}
