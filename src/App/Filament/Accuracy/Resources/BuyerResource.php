<?php

namespace App\Filament\Accuracy\Resources;

use App\Filament\Accuracy\Resources\BuyerResource\Pages;
use App\Filament\Accuracy\Resources\BuyerResource\RelationManagers;
use App\Filament\Accuracy\Resources\BuyerResource\RelationManagers\PackingListRelationManager;
use Domain\Accuracies\Models\Buyer;
use Filament\Actions\DeleteAction;
use Filament\Forms;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;

use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BuyerResource extends Resource
{
    protected static ?string $model = Buyer::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'General';

    protected static ?string $label = 'Buyers';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General Information')
                    ->description('Information about this buyer')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->label(__('Buyer Name')),
                                Forms\Components\TextInput::make('country')
                                    ->label(__('Buyer Country')),
                            ])

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->label(__('#')),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label(__('Buyer Name')),
                Tables\Columns\TextColumn::make('country')
                    ->searchable()
                    ->label(__('Buyer Country')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
                    ->visible(fn (): bool => auth()->user()->hasRole('super-admin')),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([

                    // Action::make('Activities')
                    //     ->hidden(! Auth::user()->hasCompanyRole(Auth::user()->currentCompany, 'it'))
                    //     ->icon('tabler-refresh')
                    //     ->url(fn (EntitiesBuyer $record): string => route('filament.resources.modules/packing/entities/buyers.activities', $record))
                    //     ->openUrlInNewTab(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation()
                        ->visible(fn (): bool => auth()->user()->can('buyers.delete')),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn (): bool => auth()->user()->can('buyers.deleteBulk')),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->visible(fn (): bool => auth()->user()->can('buyers.deleteBulk')),
                    Tables\Actions\RestoreBulkAction::make()
                        ->visible(fn (): bool => auth()->user()->can('buyers.restoreBulk')),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PackingListRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBuyers::route('/'),
            'create' => Pages\CreateBuyer::route('/create'),
            'edit' => Pages\EditBuyer::route('/{record}/edit'),
        ];
    }
}
