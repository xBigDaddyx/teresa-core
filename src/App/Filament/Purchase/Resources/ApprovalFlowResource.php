<?php

namespace App\Filament\Purchase\Resources;

use App\Filament\Purchase\Resources\ApprovalFlowResource\Pages;
use App\Filament\Purchase\Resources\ApprovalFlowResource\RelationManagers;
use Domain\Purchases\Models\ApprovalFlow;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Xbigdaddyx\HarmonyFlow\Models\Flow;

class ApprovalFlowResource extends Resource
{
    protected static bool $isScopedToTenant = false;
    protected static ?string $model = Flow::class;

    protected static ?string $navigationGroup = 'Approval';
    protected static ?string $navigationLabel = 'Approval Flow';
    protected static ?string $navigationIcon = 'tabler-arrows-shuffle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
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

                                return 0;
                            })
                            ->maxValue(99)


                            ->label('Approval Order'),
                        Forms\Components\Group::make()
                            ->columns(2)
                            ->schema([
                                Forms\Components\Radio::make('is_skipable')
                                    ->options([
                                        true => 'Yes',
                                        false => 'No',

                                    ])
                                    ->descriptions([
                                        true => 'this approval can be skipped to next approval order',
                                        false => 'this approval cannot be skipped to next approval order',

                                    ])
                                    ->label(__('Skipable')),
                                Forms\Components\Radio::make('is_last')
                                    ->options([
                                        true => 'Yes',
                                        false => 'No',

                                    ])
                                    ->descriptions([
                                        true => 'this approval stage is the last step approval and completed.',
                                        false => 'this approval stage is still need next step approval.',

                                    ])
                                    ->label(__('Last Stage')),
                            ]),


                    ]),


                Forms\Components\Grid::make(3)
                    ->schema([

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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label(__('Approval Order')),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('Approval Type')),
                Tables\Columns\TextColumn::make('designation.name')
                    ->label(__('Approval Designation')),
                Tables\Columns\ViewColumn::make('parameter')
                    ->label(__('Parameter'))
                    ->searchable()
                    ->view('tables.columns.specification-column'),

                Tables\Columns\IconColumn::make('is_skipable')
                    ->icon(fn (string $state): string => match ($state) {

                        '0' => 'tabler-x',
                        '1' => 'tabler-check',
                        default => 'tabler-x',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        '0' => 'danger',
                        '1' => 'success',
                        default => 'danger',
                    })
                    ->label(__('Skipable')),
                // Tables\Columns\TextColumn::make('description')
                //     ->limit(50)
                //     ->label(__('Approval Description')),
            ])
            ->defaultGroup('type')
            ->groups([
                Tables\Grouping\Group::make('type')
                    ->getDescriptionFromRecordUsing(fn (): string => 'Group by document type')
                    ->collapsible(),
                Tables\Grouping\Group::make('level')
                    ->getDescriptionFromRecordUsing(fn (): string => 'Group by approval level')
                    ->collapsible(),
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
            'index' => Pages\ListApprovalFlows::route('/'),
            'create' => Pages\CreateApprovalFlow::route('/create'),
            'edit' => Pages\EditApprovalFlow::route('/{record}/edit'),
        ];
    }
}
