<?php

namespace App\Filament\Purchase\Resources;

use App\Filament\Purchase\Resources\CurrencyResource\Pages;
use App\Filament\Purchase\Resources\CurrencyResource\RelationManagers;
use Domain\Purchases\Models\Currency;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CurrencyResource extends Resource
{
    protected static ?string $model = Currency::class;

    protected static ?string $navigationGroup = 'Supplier';
    protected static ?string $navigationLabel = 'Currencies';
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sign')
                    ->label(__('Sign')),
                Tables\Columns\TextColumn::make('single')
                    ->label(__('Single')),
                Tables\Columns\TextColumn::make('multi')
                    ->label(__('Multi')),
                Tables\Columns\TextColumn::make('rate')
                    ->label(__('Rate')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label(__('Created')),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label(__('Created By')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListCurrencies::route('/'),
            'create' => Pages\CreateCurrency::route('/create'),
            'view' => Pages\ViewCurrency::route('/{record}'),
            'edit' => Pages\EditCurrency::route('/{record}/edit'),
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
