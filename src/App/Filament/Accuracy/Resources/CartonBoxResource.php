<?php

namespace App\Filament\Accuracy\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Accuracy\Resources\CartonBoxResource\Pages;
use App\Filament\Accuracy\Resources\CartonBoxResource\RelationManagers;
use App\Filament\Accuracy\Resources\CartonBoxResource\RelationManagers\CartonBoxAttributesRelationManager;
use App\Filament\Accuracy\Resources\CartonBoxResource\RelationManagers\PackingListRelationManager;
use App\Filament\Accuracy\Resources\CartonBoxResource\RelationManagers\PolybagsRelationManager;
use App\Filament\Accuracy\Resources\CartonBoxResource\RelationManagers\PolybagTagsRelationManager;
use App\Filament\Accuracy\Resources\PackingListResource\RelationManagers\CartonBoxesRelationManager;
use Domain\Accuracies\Models\Buyer;
use Domain\Accuracies\Models\CartonBox;
use Domain\Accuracies\Models\PackingList;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Awcodes\Shout\Components\Shout;
use Filament\Facades\Filament;
use Filament\Forms\Get;
use Filament\Infolists;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Infolist;
use Filament\Resources\Components\Tab;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\Collection;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class CartonBoxResource extends Resource
{
    protected static ?string $model = CartonBox::class;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationGroup = 'Packages';

    protected static ?string $label = 'Carton Box';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Shout::make('important')
                    ->hiddenOn('create')
                    ->visible(fn (Model $record): bool => $record->isLocked())
                    ->columnSpan('full')
                    ->icon('tabler-lock')
                    ->content('This carton box is locked because its already completed!')
                    ->type('warning'),
                Forms\Components\Section::make('General Information')
                    ->schema([
                        Forms\Components\TextInput::make('box_code')
                            ->label('Box Code')
                            ->required(),
                        Forms\Components\Select::make('type')
                            ->live()
                            ->required()
                            ->options([
                                'SOLID' => 'SOLID',
                                'MULTIPLE' => 'MULTIPLE',
                                'MIX' => 'MIX',
                                'RATIO' => 'RATIO',
                            ])
                            ->label('Type'),
                        Forms\Components\Select::make('packing_list_id')
                            ->hiddenOn(CartonBoxesRelationManager::class)
                            ->relationship('packingList', 'po', modifyQueryUsing: fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant()))
                            ->required()
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "PO: {$record->po} - {$record->buyer->name} {$record->buyer->country} - {$record->style_no}"),
                        Forms\Components\TextInput::make('carton_number')
                            ->default(0)
                            ->label('Carton Number'),
                        Forms\Components\TextInput::make('size')
                            ->hidden(fn (Get $get): bool => $get('type') === 'RATIO')
                            ->label('Size'),
                        Forms\Components\TextInput::make('color')
                            ->hidden(fn (Get $get): bool => $get('type') === 'RATIO')
                            ->label('Color'),
                        Forms\Components\TextInput::make('quantity')
                            ->required()
                            ->label('Quantity'),

                        Forms\Components\Toggle::make('is_completed')
                            ->label('Completed')
                            ->hiddenOn('create')
                            ->visible(function (Model $record) {
                                if ($record->polybags->count() > 0) {
                                    if ($record->is_completed !== true) {
                                        return true;
                                    }

                                    return false;
                                }

                                return true;
                            }),

                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->label('ID'),
                Tables\Columns\TextColumn::make('box_code')
                    ->searchable()
                    ->label('Box Code'),
                Tables\Columns\TextColumn::make('packingList.po')
                    ->searchable()
                    ->label('PO'),
                Tables\Columns\TextColumn::make('carton_number')
                    ->tooltip('Carton Number')
                    ->label('CN'),
                Tables\Columns\TextColumn::make('size')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->hiddenOn(CartonBoxesRelationManager::class)
                    ->searchable()
                    ->label('Size'),
                Tables\Columns\TextColumn::make('color')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->hiddenOn(CartonBoxesRelationManager::class)
                    ->searchable()
                    ->label('Color'),
                Tables\Columns\TextColumn::make('quantity')
                    // ->summarize(Sum::make()->label('Total'))
                    ->label('Quantity'),
                Tables\Columns\TextColumn::make('type')
                    ->searchable()
                    ->label('Type'),
                // Tables\Columns\TextColumn::make('description')
                //     ->searchable()
                //     ->limit(50)
                //     ->label('Box Info'),
                Tables\Columns\IconColumn::make('is_completed')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->boolean()
                    ->trueIcon('tabler-clipboard-check')
                    ->falseIcon('tabler-clipboard-x')
                    ->label('Completed'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Completed At')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('completedBy.name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Completed By'),
                Tables\Columns\TextColumn::make('inspection_requested_by')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Inspection Requester'),
                Tables\Columns\TextColumn::make('inspection_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Inspection At')
                    ->dateTime(),
            ])
            ->groups([
                Group::make('packingList.po')
                    ->label('PO')
                    ->getDescriptionFromRecordUsing(function (CartonBox $record) {
                        return $record->packingList->buyer->name;
                    })
                    ->collapsible(),
                Group::make('is_completed')
                    ->getTitleFromRecordUsing(function (CartonBox $record) {
                        if ($record->is_completed === true) {
                            return 'Completed';
                        }
                        return 'Outstanding';
                    })
                    ->getDescriptionFromRecordUsing(function (CartonBox $record) {
                        if ($record->is_completed === true) {
                            return 'This carton box is completed and validated';
                        }
                        return 'This carton box is incompleted';
                    })
                    ->label('Status')
                    ->collapsible(),
                Group::make('type')
                    ->label('Type')
                    ->collapsible(),
            ])
            ->groupsInDropdownOnDesktop()
            ->defaultGroup('packingList.po')
            ->queryStringIdentifier('users')
            ->striped()
            ->deferLoading()

            ->filters([
                Tables\Filters\TrashedFilter::make()
                    ->visible(fn (): bool => auth()->user()->hasRole('super-admin')),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'SOLID' => 'SOLID',
                        'MULTIPLE' => 'MULTIPLE',
                        'MIX' => 'MIX',
                        'RATIO' => 'RATIO',
                    ]),
                Tables\Filters\Filter::make('buyer')
                    ->columnSpanFull()
                    ->columns(3)
                    ->form([
                        Forms\Components\Select::make('buyer_id')
                            ->reactive()
                            ->label('Buyer')
                            ->hint('Buyer Filter')
                            ->hintIcon('tabler-selector')
                            ->hintColor('primary')
                            ->options(fn () => Buyer::whereBelongsTo(Filament::getTenant())->pluck('name', 'id'))
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $buyer = Buyer::find($state);

                                if ($buyer) {
                                    $contract_no = (int) $get('contract_no');

                                    if ($contract_no && $packing_list = PackingList::where('contract_no', $contract_no)->first()) {
                                        if ($packing_list->buyer_id !== $buyer->id) {
                                            // aircraft doesn't belong to buyer, so unselect it
                                            $set('contract_no', null);
                                        }
                                    }
                                }
                            }),
                        Forms\Components\Select::make('contract_no')
                            ->hint('Contract / MO Filter')
                            ->hintIcon('tabler-selector')
                            ->hintColor('primary')
                            ->options(function (callable $get, callable $set) {
                                $buyer = Buyer::find($get('buyer_id'));

                                // if a buyer is selected, just fetch the aircraft for this buyer thru the buyer's belongsTo aircraft
                                if ($buyer) {
                                    return $buyer->packingLists->pluck('contract_no', 'contract_no');
                                }

                                // no buyer selected, so get all aircraft
                                return PackingList::whereBelongsTo(Filament::getTenant())->pluck('contract_no', 'contract_no');
                            }),
                        Forms\Components\Select::make('style_no')
                            ->hint('Contract / MO Filter')
                            ->hintIcon('tabler-selector')
                            ->hintColor('primary')
                            ->options(function (callable $get, callable $set) {
                                $buyer = Buyer::find($get('buyer_id'));

                                // if a buyer is selected, just fetch the aircraft for this buyer thru the buyer's belongsTo aircraft
                                if ($buyer && $get('contract_no')) {
                                    return $buyer->packingLists->where('contract_no', $get('contract_no'))->pluck('style_no', 'style_no');
                                }

                                // no buyer selected, so get all aircraft
                                return PackingList::whereBelongsTo(Filament::getTenant())->pluck('style_no', 'style_no');
                            }),


                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['buyer_id'],
                                fn (Builder $query, $buyer): Builder => $query->whereHas('packingList', function (Builder $q) use ($buyer) {
                                    $q->where('buyer_id', '=', $buyer);
                                }),
                            )
                            ->when(
                                $data['contract_no'],
                                fn (Builder $query, $type): Builder => $query->whereHas('packingList', function (Builder $query) use ($data) {
                                    $query->where('contract_no', '=', $data['contract_no']);
                                })
                            )
                            ->when(
                                $data['style_no'],
                                fn (Builder $query, $type): Builder => $query->whereHas('packingList', function (Builder $query) use ($data) {
                                    $query->where('style_no', '=', $data['style_no']);
                                })
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['buyer_id']) {
                            return null;
                        }
                        $buyer = Buyer::find($data['buyer_id']);

                        return 'Buyer : (' . $buyer->name . ')';
                    }),
                // Tables\Filters\SelectFilter::make('po')->relationship('packingList', 'po', fn (Builder $query) => $query->withTrashed())
                //     ->label('Purchase Order'),
                // Tables\Filters\SelectFilter::make('contract_no')->relationship('packingList', 'contract_no')
                //     ->label('Contract No'),
                // Tables\Filters\SelectFilter::make('batch')->relationship('packingList', 'batch')
                //     ->label('Batch'),

                // Tables\Filters\Filter::make('is_completed')
                //     ->label('Completed')
                //     ->query(fn (Builder $query): Builder => $query->where('is_completed', true)),
            ], FiltersLayout::AboveContentCollapsible)
            ->filtersFormColumns(4)
            ->filtersFormWidth('4xl')
            ->actions([

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('finish_inspection')
                        ->visible(fn (CartonBox $record): bool => $record->in_inspection === true)
                        ->action(fn (CartonBox $record) => $record->finishInspection())
                        ->icon('heroicon-o-clipboard-document-check')
                        ->requiresConfirmation()
                        ->modalHeading('Finish inspection')
                        ->modalDescription('Are you sure you\'d like to finish inspect this carton box?')
                        ->modalIcon('heroicon-o-clipboard-document-check')
                        ->modalIconColor('warning')
                        ->modalSubmitActionLabel('Yes, finish it.')
                        ->color('warning'),
                    Tables\Actions\Action::make('inspection')
                        ->form([
                            Forms\Components\TextInput::make('inspection_requested_by')
                                ->label('Inspection Requester')
                                ->required(),
                        ])
                        ->visible(fn (CartonBox $record): bool => $record->is_completed === true)
                        ->action(fn (array $data, CartonBox $record) => $record->inspection($data['inspection_requested_by']))
                        ->icon('heroicon-o-document-magnifying-glass')
                        ->requiresConfirmation()
                        ->modalHeading('Inspect Carton Box')
                        ->modalDescription('Are you sure you\'d like to inspect this carton box?')
                        ->modalIcon('heroicon-o-document-magnifying-glass')
                        ->modalIconColor('warning')
                        ->modalSubmitActionLabel('Yes, inspect it.')
                        ->color('warning'),
                    // Filament\Tables\Actions\Action::make('Activities')
                    //     ->hidden(!auth()->user()->hasRole('super-admin'))
                    //     ->icon('tabler-refresh')
                    //     ->url(fn (EntitiesCartonBox $record): string => route('filament.resources.carton-boxes.activities', $record))
                    //     ->openUrlInNewTab(),
                    Tables\Actions\Action::make('lock')
                        ->action(fn (CartonBox $record) => $record->lock())
                        ->icon('tabler-lock')
                        ->requiresConfirmation()
                        ->visible(fn (CartonBox $record): bool => $record->isUnlocked() && $record->is_completed === true && auth()->user()->can('lock', $record))
                        ->modalHeading('Lock Carton Box')
                        ->modalDescription('Are you sure you\'d like to lock this carton box?')
                        ->modalIcon('tabler-lock')
                        ->modalIconColor('danger')
                        ->modalSubmitActionLabel('Yes, lock it.')
                        ->color('danger'),
                    Tables\Actions\Action::make('unlock')
                        ->action(fn (Model $record) => $record->unlock())
                        ->icon('tabler-lock-open')
                        ->visible(fn (Model $record): bool => $record->isLocked() && auth()->user()->can('unlock', $record))
                        ->requiresConfirmation()
                        ->modalHeading('Unlock Carton Box')
                        ->modalDescription('Are you sure you\'d like to unlock this carton box?')
                        ->modalIcon('tabler-lock-open')
                        ->modalIconColor('success')
                        ->modalSubmitActionLabel('Yes, Unlock it.')
                        ->color('success'),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->visible(fn (CartonBox $record): bool => $record->isUnlocked() && $record->in_inspection === false),
                    Tables\Actions\DeleteAction::make()
                        ->visible(fn (): bool => auth()->user()->can('carton-boxes.delete')),
                ]),
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export'),
            ])
            ->bulkActions([
                FilamentExportBulkAction::make('Export'),
                Tables\Actions\BulkAction::make('finish_inspection')
                    // ->visible(fn (Collection $records): bool => $records->contains('in_inspection', true))
                    ->action(fn (Collection $records) => $records->each->finishInspection())
                    ->icon('heroicon-o-clipboard-document-check')
                    ->requiresConfirmation()
                    ->modalHeading('Finish inspection')
                    ->modalDescription('Are you sure you\'d like to finish inspect this carton box?')
                    ->modalIcon('heroicon-o-clipboard-document-check')
                    ->modalIconColor('warning')
                    ->modalSubmitActionLabel('Yes, finish it.')
                    ->color('warning'),
                Tables\Actions\BulkAction::make('inspection')
                    ->form([
                        Forms\Components\TextInput::make('inspection_requested_by')
                            ->label('Inspection Requester')
                            ->required(),
                    ])

                    ->action(fn (array $data, Collection $records) => $records->each->inspection($data['inspection_requested_by']))
                    ->icon('heroicon-o-document-magnifying-glass')
                    ->requiresConfirmation()
                    ->modalHeading('Inspect Carton Box')
                    ->modalDescription('Are you sure you\'d like to inspect this carton box?')
                    ->modalIcon('heroicon-o-document-magnifying-glass')
                    ->modalIconColor('warning')
                    ->modalSubmitActionLabel('Yes, inspect it.')
                    ->color('warning'),

                Tables\Actions\DeleteBulkAction::make()
                    ->visible(fn (): bool => auth()->user()->can('carton-boxes.deleteBulk')),
                Tables\Actions\ForceDeleteBulkAction::make()
                    ->visible(fn (): bool => auth()->user()->can('carton-boxes.deleteBulk')),
                Tables\Actions\RestoreBulkAction::make()
                    ->visible(fn (): bool => auth()->user()->can('carton-boxes.restoreBulk')),
            ]);
    }

    // public static function infolist(Infolist $infolist): Infolist
    // {
    //     return $infolist
    //         ->schema([
    //             Infolists\Components\Section::make('Packing List')
    //                 ->description('Packing list information for this carton box.')
    //                 ->schema([
    //                     Grid::make(2)
    //                         ->schema([
    //                             Infolists\Components\TextEntry::make('type'),
    //                             Infolists\Components\TextEntry::make('packingList.po')
    //                                 ->label('PO'),
    //                             Infolists\Components\TextEntry::make('packingList.buyer.name')
    //                                 ->label('Buyer'),
    //                         ])

    //                 ]),
    //             Infolists\Components\Section::make('Element')
    //                 ->description('Information element for this carton box.')
    //                 ->schema([
    //                     Grid::make(2)
    //                         ->schema([
    //                             Infolists\Components\TextEntry::make('size'),
    //                             Infolists\Components\TextEntry::make('color'),
    //                         ])

    //                 ]),
    //             Infolists\Components\Section::make('Identity and Quantity')
    //                 ->description('Information about identity and quantity for this carton box.')
    //                 ->schema([
    //                     Grid::make(2)
    //                         ->schema([
    //                             Infolists\Components\TextEntry::make('box_code'),

    //                             Infolists\Components\TextEntry::make('carton_number'),

    //                             Infolists\Components\TextEntry::make('quantity'),
    //                             // Infolists\Components\ViewEntry::make('is_completed')
    //                             //     ->view('components.status'),
    //                             // Infolists\Components\IconEntry::make('is_completed')
    //                             //     ->label('Completed')
    //                             //     ->boolean(),
    //                         ]),

    //                 ]),



    //         ]);
    // }
    public static function getRelations(): array
    {
        return [
            RelationGroup::make('Polybags', [
                PolybagsRelationManager::class,
                PolybagTagsRelationManager::class,
            ]),
            CartonBoxAttributesRelationManager::class,
            AuditsRelationManager::class,
            //PackingListRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCartonBoxes::route('/'),
            'create' => Pages\CreateCartonBox::route('/create'),
            'view' => Pages\ViewCartonBox::route('/{record}'),
            'edit' => Pages\EditCartonBox::route('/{record}/edit'),
        ];
    }
}
