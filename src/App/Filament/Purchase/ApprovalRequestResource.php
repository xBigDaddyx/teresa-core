<?php

namespace App\Filament\Purchase;

use App\Events\MakeOrderEvent;
use App\Events\RequestApproved;
use App\Filament\Purchase\Resources\ApprovalRequestResource\Pages;
use App\Filament\Purchase\Resources\ApprovalRequestResource\RelationManagers;
use App\Filament\Purchase\Resources\ApprovalRequestResource\RelationManagers\CommentsRelationManager;
use App\Tables\Columns\ProductColumn;
// use Domain\Purchases\Models\ApprovalRequest;
use Domain\Purchases\Models\Category;
use Domain\Purchases\Models\Comment;
use Domain\Purchases\Models\Order;
use Domain\Purchases\Models\OrderItem;
use Domain\Purchases\Models\Request;
use Domain\Purchases\Models\Supplier;
use Domain\Users\Models\User;
use Filament\Actions\StaticAction;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;
use Pboivin\FilamentPeek\Tables\Actions\ListPreviewAction;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Xbigdaddyx\HarmonyFlow\Jobs\UpdateApprovalRecord;
use Xbigdaddyx\HarmonyFlow\Models\Approval;

class ApprovalRequestResource extends Resource
{
    use HasPreviewModal;
    protected static ?string $model =  Approval::class;

    protected static ?string $navigationGroup = 'Approval';
    protected static ?string $navigationIcon = 'tabler-report';

    public static function getPluralModelLabel(): string
    {
        if (auth('ldap')->user()->hasRole('purchase-officer')) {
            return __('Purchase Requested');
        }
        return __('Approvals');
    }
    public static function getNavigationLabel(): string
    {
        if (auth('ldap')->user()->hasRole('purchase-officer')) {
            return __('Purchase Requested');
        }
        return __('Approvals');
    }
    public static function getNavigationBadge(): ?string
    {

        return static::getModel()::whereBelongsTo(auth('ldap')->user())->count() ?? 0;
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::whereBelongsTo(auth('ldap')->user())->count() > 10 ? 'warning' : 'primary';
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
            ->poll('5s')
            ->columns([
                Tables\Columns\TextColumn::make('approvable.request_number')
                    ->label('Request Number'),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('Type')),
                Tables\Columns\TextColumn::make('approvable.category.name')
                    ->badge()
                    ->label('Category'),
                Tables\Columns\TextColumn::make('approvable.department.name')
                    ->searchable()
                    ->label(__('Department')),
                Tables\Columns\TextColumn::make('approvable.customer')
                    ->searchable()
                    ->label(__('Customer / Buyer')),
                Tables\Columns\TextColumn::make('approvable.contract_no')
                    ->searchable()
                    ->label(__('Contract')),
                ProductColumn::make('approvable.requestItems')
                    ->color('info')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(1)
                    ->label(__('Items')),
                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('Processed At')),
                Tables\Columns\TextColumn::make('approvable.note')
                    ->label(__('Note')),

                // Tables\Columns\TextColumn::make('approvable.request_items_count')->counts('approvable.requestItems')
                //     ->badge()
                //     ->label(__('Items Requested')),
            ])

            ->modifyQueryUsing(function (Builder $query) {
                if (auth('ldap')->user()->hasRole('super-admin')) {
                    return $query;
                }
                return $query->whereBelongsTo(auth('ldap')->user());
            })

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    // Tables\Actions\ActionGroup::make([
                    ListPreviewAction::make()

                        ->previewModalData(fn (Model $record) => ['record' => $record->approvable])
                        ->label(__('View'))
                        ->color('info'),
                    Tables\Actions\Action::make('order')
                        ->color('primary')
                        ->visible(fn (): bool => auth('ldap')->user()->hasRole('purchase-officer'))
                        ->label('Make Order')
                        ->icon('tabler-files')
                        ->steps([
                            Step::make('Supplier')
                                ->description('Supplier information')
                                ->schema([
                                    Forms\Components\Select::make('supplier_id')
                                        ->label(__('Supplier'))
                                        // ->options(function () {
                                        //     return $suppliers = Supplier::whereBelongsTo(Filament::getTenant())->pluck('name', 'id');
                                        // })
                                        ->searchable()
                                        ->getSearchResultsUsing(fn (string $search): array => Supplier::where('name', 'like', "%{$search}%")->whereBelongsTo(Filament::getTenant())->limit(50)->pluck('name', 'id')->toArray())
                                        ->getOptionLabelsUsing(fn (array $values): array => Supplier::whereIn('id', $values)->whereBelongsTo(Filament::getTenant())->pluck('name', 'id')->toArray())
                                        ->required(),
                                    Forms\Components\DatePicker::make('delivery_date')
                                        ->label(__('Delivery Date'))
                                        ->required(),

                                ])
                                ->columns(2),
                            Step::make('Order')
                                ->description('Order information')
                                ->schema([
                                    Forms\Components\Select::make('category_id')
                                        ->label(__('Order Category'))
                                        ->options(function () {
                                            return $category = Category::whereBelongsTo(Filament::getTenant())->pluck('name', 'id');
                                        })
                                        ->live()
                                        ->required(),
                                    Forms\Components\TextInput::make('capex_code')
                                        ->hidden(function (Get $get) {
                                            if ($get('category_id')) {
                                                $category = Category::find($get('category_id'));
                                                if ($category->name === 'CAPEX') {
                                                    return false;
                                                }
                                            }
                                            return true;
                                        })
                                        ->label(__('Capex Code')),
                                    Forms\Components\Textarea::make('payment_term')
                                        ->label(__('Payment Term'))
                                        ->required(),
                                    Forms\Components\Section::make('Tax')
                                        ->description('Tax information')
                                        ->columns(2)
                                        ->schema([
                                            Forms\Components\Toggle::make('included_tax')
                                                ->onColor('success')
                                                ->offColor('danger')
                                                ->onIcon('tabler-check')
                                                ->offIcon('tabler-x')
                                                ->inline(false)
                                                ->label(__('Tax Invoice'))
                                                ->live(),
                                            Forms\Components\Select::make('Tax Type')
                                                ->hidden(fn (Get $get): bool => $get('included_tax') === false)
                                                ->label(__('Tax Type'))
                                                ->options([
                                                    'PPN' => 'PPN',
                                                    'PPH' => 'PPH',

                                                ]),
                                        ]),
                                    Forms\Components\Textarea::make('comment')
                                        ->label(__('Comment'))
                                        ->columnSpanFull(),
                                ])
                                ->columns(2),


                        ])
                        ->action(function (array $data, Approval $record): void {
                            $record->is_completed = true;
                            $record->completed_at = now();
                            $record->approvable->is_processed = true;
                            $record->approvable->processed_by = auth('ldap')->user()->id;
                            $record->approvable->setStatus('Processed', 'Processed by ' . auth('ldap')->user()->name);
                            $record->save();


                            MakeOrderEvent::dispatch($data, $record);
                        }),
                    Tables\Actions\Action::make('approve')

                        ->hidden(fn (Approval $record): bool => $record->is_completed || (int)$record->chargeable_id === auth('ldap')->user()->id)
                        // ->action(fn (Approval $record) => RequestApproved::dispatch($record))

                        ->icon('tabler-check')
                        ->color('success')
                        ->action(function (Approval $record) {
                            $record->setStatus('Approved', 'Approved by ' . auth('ldap')->user()->name);
                            $record->is_approved = true;
                            $record->approved_at = now();
                            $record->is_completed = true;
                            $record->completed_at = now();
                            $record->save();

                            if ($record->approvable->getNextPerson() === 'Completed') {
                                $charge = User::whereHas('designations', function (Builder $query) {
                                    $query->where('name', 'Purchasing')->whereBelongsTo(Filament::getTenant());
                                })->first();
                                $model = new Approval();
                                $model->type = $record->type;
                                $model->approvable_id = $record->approvable_id;
                                $model->flow_id = null;
                                $model->approvable_type = $record->approvable_type;
                                $model->chargeable_id = $charge->id;
                                $model->chargeable_type = get_class($charge);
                                $model->created_by = auth('ldap')->user()->id;
                                $model->updated_by = auth('ldap')->user()->id;
                                $model->company_id = auth('ldap')->user()->company->id;
                                $model->department_id = $record->department_id;
                                $model->save();
                            }
                        }),
                    Tables\Actions\Action::make('reject')
                        ->hidden(fn (Approval $record): bool => $record->is_completed || (int)$record->chargeable_id === auth('ldap')->user()->id)

                        ->icon('tabler-clipboard-x')
                        ->color('danger')
                        ->modalIcon('tabler-clipboard-x')
                        ->modalHeading('Reject approval')
                        ->modalDescription('Are you sure you\'d like to reject this approval? This cannot be undone.')
                        ->form([
                            Textarea::make('reason')
                                ->label('Reason'),
                        ])
                        ->action(function (array $data, Approval $record) {
                            $record->setStatus('Rejected', $data['reason'] ?? 'Rejected by ' . auth('ldap')->user()->name);
                            $record->is_rejected = true;
                            $record->rejected_at = now();
                            $record->is_completed = true;
                            $record->completed_at = now();
                            $record->save();
                        }),
                    Tables\Actions\Action::make('Comment')

                        ->stickyModalHeader()
                        ->outlined()
                        ->icon('tabler-message-2')
                        ->badge(fn (Approval $record) => $record->approvable->comments->count())
                        ->badgeColor('info')
                        ->modalAlignment(Alignment::Center)
                        ->modalIcon('tabler-message-2')
                        ->modalHeading('Comments')
                        ->modalSubmitAction(false)
                        ->modalCancelAction(fn (StaticAction $action) => $action->label('Close'))
                        ->modalContent(fn (Approval $record): View => view(
                            'filament.purchase.resources.approval-request-resource.pages.comment-approval-request',
                            ['record' => $record->approvable],
                        ))
                        ->closeModalByClickingAway(false)


                ]),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApprovalRequests::route('/'),
            'create' => Pages\CreateApprovalRequest::route('/create'),
            'edit' => Pages\EditApprovalRequest::route('/{record}/edit'),
        ];
    }
}
