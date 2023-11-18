<?php

namespace App\Filament\Purchase\Resources;

use App\Filament\Purchase\Resources\RequestItemResource\Pages;
use App\Filament\Purchase\Resources\RequestItemResource\RelationManagers;
use Domain\Purchases\Models\RequestItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Query\Builder as QueryBuilder;

class RequestItemResource extends Resource
{
    protected static ?string $model = RequestItem::class;

    protected static ?string $navigationGroup = 'Purchase';
    protected static ?string $navigationLabel = 'Requested Items';
    protected static ?string $navigationIcon = 'tabler-checkup-list';
    public static function shouldRegisterNavigation(): bool
    {
        return auth('ldap')->user()->hasRole('purchase-officer');
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('request', function (Builder $query) {
            $query->where('approval_status', 'Approval Completed');
        });
    }
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
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListRequestItems::route('/'),
            'create' => Pages\CreateRequestItem::route('/create'),
            'edit' => Pages\EditRequestItem::route('/{record}/edit'),
        ];
    }
}
