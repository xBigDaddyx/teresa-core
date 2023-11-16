<?php

namespace App\Filament\Purchase\Resources;

use App\Filament\Purchase\Resources\ProductResource\Pages;
use App\Filament\Purchase\Resources\ProductResource\RelationManagers;
use Domain\Purchases\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Pboivin\FilamentPeek\Tables\Actions\ListPreviewAction;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationGroup = 'Product';
    protected static ?string $navigationLabel = 'Product List';
    protected static ?string $navigationIcon = 'tabler-paper-bag';

    public static function form(Form $form): Form
    {
        return $form
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
                        Forms\Components\SpatieMediaLibraryFileUpload::make('photo')
                            ->collection('products')->columnSpanFull()->multiple(),

                    ]),
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('photo')
                    ->collection('products')->conversion('thumb'),
                Tables\Columns\TextColumn::make('product_number')
                    ->searchable()
                    ->badge()
                    ->label(__('Product Number')),
                Tables\Columns\TextColumn::make('category.name')
                    ->badge()
                    ->label(__('Product Category')),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label(__('Name')),

                Tables\Columns\ViewColumn::make('specification')
                    ->searchable()
                    ->view('tables.columns.specification-column'),
                Tables\Columns\TextColumn::make('unit.name')

                    ->label(__('Unit')),

            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
                    ->visible(auth('ldap')->user()->hasRole('super-admin')),
                Tables\Filters\SelectFilter::make('product_category_id')
                    ->label(__('Product Category'))
                    ->relationship('category', 'name'),
            ])
            ->actions([
                ListPreviewAction::make()
                    ->label('View'),
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make()
                        ->visible(auth('ldap')->user()->hasRole('super-admin')),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            // 'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
