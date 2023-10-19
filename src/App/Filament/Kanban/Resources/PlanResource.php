<?php

namespace App\Filament\Kanban\Resources;

use App\Filament\Kanban\Resources\PlanResource\Pages;
use App\Filament\Kanban\Resources\PlanResource\RelationManagers;
use Domain\Kanban\Models\Plan;
use Domain\Kanban\Models\Sewing;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static ?string $navigationGroup = 'Production';
    protected static ?string $navigationLabel = 'Plans';
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Sewing')

                            ->schema([
                                Forms\Components\Select::make('sewing_id')
                                    ->label('Sewing')
                                    ->options(function (callable $get) {
                                        return Sewing::where('company_id', $get('company_id'))->pluck('id', 'id');
                                    })
                                    ->required(),
                            ])->columns(1),
                    ]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Buyer')
                            ->schema([
                                Forms\Components\TextInput::make('buyer')
                                    ->required()
                                    ->columnSpan(2)
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('contract_id')
                                    ->label('Contract')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('style_id')
                                    ->label('Style')
                                    ->required()
                                    ->maxLength(255),

                            ])->columns(2),
                    ]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Quantity and Date')
                            ->schema([
                                Forms\Components\TextInput::make('plan_qty')
                                    ->numeric()
                                    ->label('Plan Quantity')
                                    ->required(),
                                Forms\Components\DatePicker::make('sewing_start_date')
                                    ->label('Sewing Start')
                                    ->required(),
                                Forms\Components\DatePicker::make('sewing_end_date')
                                    ->label('Sewing End')
                                    ->required(),
                                Forms\Components\DatePicker::make('exit_fty_date')
                                    ->label('Exit Factory')
                                    ->required(),
                            ])->columns(2)
                    ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sewing_id')
                    ->sortable()
                    ->label('Sewing Line'),
                Tables\Columns\TextColumn::make('buyer')
                    ->sortable()
                    ->label('Buyer'),
                Tables\Columns\TextColumn::make('contract_id')
                    ->sortable()
                    ->searchable()
                    ->label('Contract'),
                Tables\Columns\TextColumn::make('style_id')
                    ->sortable()
                    ->searchable()
                    ->label('Style'),
                Tables\Columns\TextColumn::make('plan_qty')
                    ->badge()
                    ->separator(',')
                    ->label('Plan QTY')
                    ->colors(['secondary'])
                    ->icons(['heroicon-o-calculator']),
                Tables\Columns\TextColumn::make('sewing_start_date')
                    ->badge()
                    ->separator(',')
                    ->label('Sewing Start')
                    ->colors(['success'])
                    ->icons(['heroicon-o-calendar'])
                    ->date(),
                Tables\Columns\TextColumn::make('sewing_end_date')
                    ->badge()
                    ->separator(',')
                    ->label('Sewing End')
                    ->colors(['warning'])
                    ->icons(['heroicon-o-calendar'])
                    ->date(),
                Tables\Columns\TextColumn::make('exit_fty_date')
                    ->badge()
                    ->separator(',')
                    ->label('Exit Factory')
                    ->colors(['danger'])
                    ->icons(['heroicon-o-calendar'])
                    ->date(),
                Tables\Columns\TextColumn::make('created_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('sewing_id')
                    ->label(__('Sewing'))
                    ->options(Sewing::whereBelongsTo(Filament::getTenant())->pluck('id', 'id')),
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
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}
