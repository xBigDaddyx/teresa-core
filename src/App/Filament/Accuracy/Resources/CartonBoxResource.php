<?php

namespace App\Filament\Accuracy\Resources;

use App\Filament\Accuracy\Resources\CartonBoxResource\Pages;
use App\Filament\Accuracy\Resources\CartonBoxResource\RelationManagers;
use App\Filament\Accuracy\Resources\CartonBoxResource\RelationManagers\CartonBoxAttributesRelationManager;
use App\Filament\Accuracy\Resources\CartonBoxResource\RelationManagers\PackingListRelationManager;
use App\Filament\Accuracy\Resources\CartonBoxResource\RelationManagers\PolybagsRelationManager;
use App\Filament\Accuracy\Resources\CartonBoxResource\RelationManagers\PolybagTagsRelationManager;
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
use Filament\Resources\RelationManagers\RelationGroup;

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
                        Forms\Components\Select::make('packing_list_id')
                            ->relationship('packingList', 'po')
                            ->required()
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "PO: {$record->po} - {$record->buyer->name} {$record->buyer->country} - {$record->style_no}"),
                        Forms\Components\TextInput::make('carton_number')
                            ->default(0)
                            ->label('Carton Number'),
                        Forms\Components\TextInput::make('size')
                            ->hidden(fn (Model $record): bool => $record->type === 'RATIO')
                            ->label('Size'),
                        Forms\Components\TextInput::make('color')
                            ->hidden(fn (Model $record): bool => $record->type === 'RATIO')
                            ->label('Color'),
                        Forms\Components\TextInput::make('quantity')
                            ->required()
                            ->label('Quantity'),
                        Forms\Components\Select::make('type')
                            ->required()
                            ->options([
                                'SOLID' => 'SOLID',
                                'MULTIPLE' => 'MULTIPLE',
                                'MIX' => 'MIX',
                                'RATIO' => 'RATIO',
                            ])
                            ->label('Type'),
                        Forms\Components\Toggle::make('is_completed')
                            ->label('Completed')
                            ->visible(function (Model $record) {
                                if ($record->polybags->count() > 0) {
                                    if ($record->is_completed !== true) {
                                        return true;
                                    }

                                    return false;
                                }

                                return true;
                            }),
                        // Checkbox::make('is_completed')
                        //     ->label('Completed'),
                        // Textarea::make('description')
                        //     ->label('Box Info')
                        //     ->columnSpan(2),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id')
                    ->label('#'),
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
                    ->searchable()
                    ->label('Size'),
                Tables\Columns\TextColumn::make('color')
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

                    ->searchable()
                    ->boolean()
                    ->trueIcon('tabler-clipboard-check')
                    ->falseIcon('tabler-clipboard-x')
                    ->label('Completed'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
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
                            ->options(fn () => Buyer::pluck('name', 'id'))
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
                                    return $buyer->lists->pluck('contract_no', 'contract_no');
                                }

                                // no buyer selected, so get all aircraft
                                return PackingList::all()->pluck('contract_no', 'contract_no');
                            }),
                        Forms\Components\Select::make('style_no')
                            ->hint('Contract / MO Filter')
                            ->hintIcon('tabler-selector')
                            ->hintColor('primary')
                            ->options(function (callable $get, callable $set) {
                                $buyer = Buyer::find($get('buyer_id'));

                                // if a buyer is selected, just fetch the aircraft for this buyer thru the buyer's belongsTo aircraft
                                if ($buyer && $get('contract_no')) {
                                    return $buyer->lists->where('contract_no', $get('contract_no'))->pluck('style_no', 'style_no');
                                }

                                // no buyer selected, so get all aircraft
                                return PackingList::all()->pluck('style_no', 'style_no');
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

                Tables\Filters\Filter::make('is_completed')
                    ->label('Completed')
                    ->query(fn (Builder $query): Builder => $query->where('is_completed', true)),
            ], FiltersLayout::AboveContentCollapsible)
            ->filtersFormColumns(4)
            ->filtersFormWidth('4xl')
            ->actions([
                Tables\Actions\ActionGroup::make([
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
                        ->action(fn (CartonBox $record) => $record->unlock())
                        ->icon('tabler-lock-open')
                        ->visible(fn (CartonBox $record): bool => $record->isLocked() && auth()->user()->can('unlock', $record))
                        ->requiresConfirmation()
                        ->modalHeading('Unlock Carton Box')
                        ->modalDescription('Are you sure you\'d like to unlock this carton box?')
                        ->modalIcon('tabler-lock-open')
                        ->modalIconColor('success')
                        ->modalSubmitActionLabel('Yes, Unlock it.')
                        ->color('success'),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->visible(fn (CartonBox $record): bool => $record->isUnlocked()),
                    Tables\Actions\DeleteAction::make()
                        ->visible(fn (): bool => auth()->user()->can('carton-boxes.delete')),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->visible(fn (): bool => auth()->user()->can('carton-boxes.deleteBulk')),
                Tables\Actions\ForceDeleteBulkAction::make()
                    ->visible(fn (): bool => auth()->user()->can('carton-boxes.deleteBulk')),
                Tables\Actions\RestoreBulkAction::make()
                    ->visible(fn (): bool => auth()->user()->can('carton-boxes.restoreBulk')),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationGroup::make('Polybags', [
                PolybagsRelationManager::class,
                PolybagTagsRelationManager::class,
            ]),
            CartonBoxAttributesRelationManager::class,
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
