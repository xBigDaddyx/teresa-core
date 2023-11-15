<?php

namespace App\Filament\Kanban\Resources;

use App\Filament\Kanban\Resources\PlanQueueResource\Pages\CardList;
use App\Filament\Kanban\Resources\PlanQueueResource\Pages\ManageQueue;
use App\Filament\Kanban\Resources\PlanResource\Pages;
use App\Filament\Kanban\Resources\PlanResource\Pages\ListPlanQueues;
use App\Filament\Kanban\Resources\PlanResource\RelationManagers;
use App\Jobs\SwitchPlanJob;
use Closure;
use Domain\Kanban\Models\Plan;
use Domain\Kanban\Models\PlanQueue;
use Domain\Kanban\Models\Sewing;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;

class PlanQueueResource extends Resource
{
    protected static ?string $model = PlanQueue::class;
    protected static ?string $navigationGroup = 'Setting';

    protected static ?string $label = 'Queues';

    protected static ?string $navigationIcon = 'heroicon-o-forward';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Sewing Plan')
                            ->schema([
                                Forms\Components\Select::make('sewing_id')
                                    ->hint('Select sewing line')
                                    ->live()
                                    ->options(Sewing::whereBelongsTo(Filament::getTenant())->pluck('id', 'id'))
                                    ->label('Sewing'),
                                Forms\Components\Select::make('plan_id')
                                    ->hint('Select sewing plan')
                                    ->relationship('plan', 'contract_id', fn (Builder $query, Get $get) => $query->where('sewing_id', $get('sewing_id')))
                                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->sewing_id} | Contract {$record->contract_id}| Style {$record->style_id} | Qty {$record->plan_qty} | Start {$record->sewing_start_date} | End {$record->sewing_end_date}")
                                    ->label('Plan')
                                    ->required(),
                            ]),

                    ]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Status & Order')
                            ->schema([
                                Forms\Components\Radio::make('status')
                                    ->hint('Select status for this queue')

                                    ->options(function (Get $get) {
                                        $sewing = $get('sewing_id');
                                        if ($sewing) {
                                            $queues = PlanQueue::whereHas('plan', function (Builder $query) use ($sewing) {
                                                $query->where('sewing_id', $sewing);
                                            })->where('status', 'Active')->orWhere('status', 'Delayed')->orderBy('queue_order', 'desc')->get();

                                            if ($queues->count() > 0) {
                                                return [
                                                    'Ongoing' => 'Ongoing',
                                                ];
                                            }
                                        }

                                        return [
                                            'Active' => 'Active',
                                            'Ongoing' => 'Ongoing',
                                        ];
                                    })
                                    ->descriptions([
                                        'Active' => 'This queue will calculate as active plan',
                                        'Ongoing' => 'This queue will set as ongoing after active or delayed queue',

                                    ])
                                    ->default('Ongoing')
                                    ->required(),
                                Forms\Components\TextInput::make('queue_order')
                                    ->hint('Define queue order')
                                    ->hidden(fn (Get $get): bool => $get('sewing_id') == null || $get('sewing_id') == '')
                                    ->required()
                                    ->minValue(function (Get $get) {
                                        $sewing = $get('sewing_id');
                                        if ($sewing) {
                                            $queues = PlanQueue::whereHas('plan', function (Builder $query) use ($sewing) {
                                                $query->where('sewing_id', $sewing);
                                            })->orderBy('queue_order', 'desc')->value('queue_order');

                                            if ((int)$queues > 0) {

                                                return (int)$queues + 1;
                                            }
                                        }

                                        return 0;
                                    })

                                    ->numeric()
                                    ->label('Queue Order'),
                            ]),

                    ]),
                Forms\Components\MarkdownEditor::make('description')
                    ->label('Description')
                    ->columnSpanFull(),

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    Tables\Columns\TextColumn::make('plan.sewing.display_name')
                        ->formatStateUsing(fn (string $state): string => __("Sewing : {$state}"))
                        ->sortable()
                        ->searchable()
                        ->label('Sewing')
                        ->weight(FontWeight::Bold)
                        ->size(TextColumn\TextColumnSize::Large),

                    Tables\Columns\TextColumn::make('plan.customer.name')
                        ->formatStateUsing(fn (string $state): string => __("Buyer : {$state}"))
                        ->sortable()
                        ->searchable()
                        ->label('Buyer'),



                ]),
                Split::make([
                    Tables\Columns\TextColumn::make('status')
                        ->formatStateUsing(fn (string $state): string => __("Status : {$state}"))
                        ->badge()
                        ->sortable()
                        ->searchable()
                        ->color(function (Model $record) {
                            if ($record->status === 'Active') {
                                return 'success';
                            } else if ('Delayed') {
                                return 'primary';
                            }
                            return 'secondary';
                        })
                        ->extraAttributes([
                            'class' => 'font-bold text-2xl'
                        ]),
                    Tables\Columns\TextColumn::make('queue_order')
                        ->formatStateUsing(fn (string $state): string => __("Queue order : {$state}"))
                        ->sortable()
                        ->searchable()
                        ->extraAttributes([
                            'class' => 'font-bold text-2xl'
                        ])
                        ->label('Queue Order'),
                ]),
                Panel::make([
                    Tables\Columns\TextColumn::make('plan.contract_id')
                        ->formatStateUsing(fn (string $state): string => __("Contract : {$state}"))
                        ->sortable()
                        ->label('Contract No'),
                    Tables\Columns\TextColumn::make('plan.style_id')
                        ->formatStateUsing(fn (string $state): string => __("Style : {$state}"))
                        ->sortable()
                        ->label('Style No'),
                    Tables\Columns\TextColumn::make('plan.sewing_start_date')

                        ->badge()
                        ->sortable()
                        ->searchable()
                        ->label('Sewing Start')
                        ->colors(['success'])
                        ->icons(['heroicon-o-calendar'])
                        ->date()
                        ->formatStateUsing(fn (string $state): string => __("Start Date : {$state}")),
                    Tables\Columns\TextColumn::make('plan.sewing_end_date')

                        ->badge()
                        ->sortable()
                        ->searchable()
                        ->label('Sewing End')
                        ->colors(['warning'])
                        ->icons(['heroicon-o-calendar'])
                        ->date()
                        ->formatStateUsing(fn (string $state): string => __("End Date : {$state}")),
                    Tables\Columns\TextColumn::make('plan.exit_fty_date')


                        ->badge()
                        ->sortable()
                        ->searchable()
                        ->label('Exit Factory')
                        ->colors(['danger'])
                        ->icons(['heroicon-o-calendar'])
                        ->date()
                        ->formatStateUsing(fn (string $state): string => __("Exit Fty : {$state}")),


                ])->collapsible(),


            ])

            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Delayed' => 'Delayed',
                        'Completed' => 'Completed',
                        'Ongoing' => 'Ongoing',
                    ])
                    ->label('Status'),
                //
            ])
            ->actions([

                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('completed')
                    ->icon('tabler-check')
                    ->action(fn (Model $record) => SwitchPlanJob::dispatch($record))
                    ->color('success')
                    ->label('Set Completed')
                    ->hidden(function (Model $record) {
                        $queues = PlanQueue::whereHas('plan', function (Builder $query) use ($record) {
                            $query->where('sewing_id', $record->plan->sewing_id);
                        })->where('queue_order', '>', $record->queue_order)->orderBy('queue_order', 'ASC')->get();
                        if ($queues->count() > 0 && $record->status !== 'Completed') {
                            return false;
                        }
                        return true;
                    })
                    // ->visible(fn (Model $record): bool => $record->status !== 'Completed')
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('delayed')
                    ->icon('tabler-list-search')
                    ->action(function (Model $record) {
                        $queue = PlanQueue::find($record->id);
                        $queue->status = 'Delayed';
                        $queue->save();
                    })
                    ->color('primary')
                    ->label('Set Delayed')
                    ->visible(fn (Model $record): bool => $record->status !== 'Delayed' && $record->status !== 'Completed')
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('active')
                    ->icon('tabler-run')
                    ->action(function (Model $record) {
                        $queue = PlanQueue::find($record->id);
                        $queue->status = 'Active';
                        $queue->save();
                    })
                    ->color('primary')
                    ->label('Set Active')
                    ->visible(fn (Model $record): bool => $record->status !== 'Active' && $record->status !== 'Completed' && $record->status !== 'Delayed')
                    ->requiresConfirmation(),

            ])
            ->bulkActions([
                //
            ])

            ->defaultGroup('plan.sewing.display_name')
            ->groups([
                Group::make('status')

                    ->label('Status'),

                Group::make('plan.sewing.display_name')

                    ->label('Sewing'),
                Group::make('plan.contract_id')

                    ->label('Contract'),
                Group::make('plan.style_id')

                    ->label('Style'),
                Group::make('plan.buyer')

                    ->label('Buyer'),
            ])
            ->reorderable('queue_order')
            ->contentGrid([
                'md' => 1,
                'xl' => 2,
            ]);
    }
    public static function getPages(): array
    {
        return [

            'index' => ListPlanQueues::route('/'),
            'create' => Pages\CreatePlanQueue::route('/create'),

        ];
    }
}
