<?php

namespace App\Filament\Accuracy\Resources;

use App\Filament\Accuracy\Resources\PackingListResource\Pages;
use Domain\Accuracies\Models\PackingList;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PackingListResource extends Resource
{
    protected static ?string $model = PackingList::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Packages';

    protected static ?string $label = 'Packing List';

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
                Tables\Columns\TextColumn::make('po')
                    ->searchable()
                    ->sortable()
                    ->label('PO'),
                // Tables\Columns\TextColumn::make('buyer.name')
                //     ->searchable()
                //     ->sortable()
                //     ->label('Buyer'),
                // Tables\Columns\TextColumn::make('buyer.country')
                //     ->searchable()
                //     ->sortable()
                //     ->label('Buyer Country'),
                Tables\Columns\TextColumn::make('style_no')
                    ->searchable()
                    ->sortable()
                    ->label('Style'),
                Tables\Columns\TextColumn::make('contract_no')
                    ->searchable()
                    ->sortable()
                    ->label('Contract'),
                Tables\Columns\TextColumn::make('batch')
                    ->searchable()
                    ->sortable()
                    ->label('Batch'),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->label('Description'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Created'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePackingLists::route('/'),
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
