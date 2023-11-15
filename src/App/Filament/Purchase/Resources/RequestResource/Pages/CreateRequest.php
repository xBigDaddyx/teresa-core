<?php

namespace App\Filament\Purchase\Resources\RequestResource\Pages;

use App\Filament\Purchase\Resources\RequestResource;
use Domain\Purchases\Models\Product;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CreateRequest extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;
    protected static string $resource = RequestResource::class;
    protected function getSteps(): array
    {
        return [
            Forms\Components\Wizard\Step::make('Request Information')
                ->description('Request Form')
                ->icon('heroicon-o-document-text')
                ->schema([
                    Forms\Components\Grid::make(3)
                        ->schema([
                            Forms\Components\Select::make('category_id')
                                ->relationship('category', 'name')
                                ->required()
                                ->label(__('Category'))
                                ->live(),
                            Forms\Components\TextInput::make('customer')
                                ->label(__('Customer / Buyer')),
                            Forms\Components\TextInput::make('contract_no')
                                ->label(__('Contract')),
                            Forms\Components\Textarea::make('note')->columnSpanFull(),

                        ])

                ]),
            Forms\Components\Wizard\Step::make('Item List')
                ->description('List of items requested')
                ->icon('tabler-list-details')
                ->schema([

                    Forms\Components\Repeater::make('requestItems')
                        ->label('Requested')
                        ->relationship()
                        ->columns(4)
                        ->schema([
                            Forms\Components\Select::make('product_id')
                                ->relationship('product', 'name', modifyQueryUsing: function (Builder $query, Get  $get) {
                                    $query->whereHas('category', function (Builder $b) use ($get) {
                                        $b->where('category_id', $get('../../category_id'))->orWhere('category_id', '3');
                                    });
                                })
                                ->getOptionLabelFromRecordUsing(function (Model $record) {
                                    if (count($record->specification) > 0) {
                                        $collection = collect($record->specification);
                                        $value = $collection->implode('value', ' ');
                                    } else {
                                        $value = null;
                                    }

                                    return '<span class="font-bold text-primary-500">' . $record->product_number . '</span> - ' . $record->name . ' ' . $value . ' - ' . $record->unit->name;
                                })
                                ->searchDebounce(500)
                                ->searchable(['name'])
                                ->label(__('Product'))
                                ->prefixIcon('tabler-paper-bag')
                                ->allowHtml()
                                ->prefixIconColor('primary')
                                ->columnSpanFull(),
                            Forms\Components\TextInput::make('quantity')

                                ->prefixIcon('tabler-calculator')
                                ->prefixIconColor('primary')
                                ->required()
                                ->numeric()
                                ->default(1)
                                ->minValue(1)
                                ->maxValue(99),
                            Forms\Components\DatePicker::make('delivery_date')
                                ->suffixIconColor('primary')
                                ->required(),
                            Forms\Components\TextInput::make('stock')
                                ->prefixIcon('tabler-calculator')
                                ->prefixIconColor('primary')
                                ->default(0)
                                ->minValue(0)
                                ->maxValue(99)
                                ->numeric(),
                            Forms\Components\TextInput::make('style_no'),
                            Forms\Components\Textarea::make('remark')->columnSpanFull(),
                        ])->columnSpanFull()->collapsible()->itemLabel(function (array $state) {
                            $product = Product::with('unit')->find($state['product_id']);
                            if ($product) {
                                if (count($product->specification) > 0) {
                                    $collection = collect($product->specification);
                                    $value = $collection->implode('value', ' ');
                                } else {
                                    $value = null;
                                }
                                return $product->product_number . ' - ' . $product->name . ' ' . $value . ' - ' . $state['quantity'] . ' ' . $product->unit->name;
                            }
                            return null;
                        }),
                ])

        ];
    }
}
