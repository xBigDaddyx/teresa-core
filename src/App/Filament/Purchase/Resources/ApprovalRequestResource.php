<?php

namespace App\Filament\Purchase\Resources;

use App\Events\MakeOrderEvent;
use App\Events\RequestApproved;
use App\Filament\Purchase\Resources\ApprovalRequestResource\Pages;
use App\Filament\Purchase\Resources\ApprovalRequestResource\RelationManagers;
use App\Filament\Purchase\Resources\ApprovalRequestResource\RelationManagers\CommentsRelationManager;
use Domain\Purchases\Models\ApprovalRequest;
use Domain\Purchases\Models\Category;
use Domain\Purchases\Models\Comment;
use Domain\Purchases\Models\Order;
use Domain\Purchases\Models\OrderItem;
use Domain\Purchases\Models\Supplier;
use Filament\Actions\StaticAction;
use Filament\Facades\Filament;
use Filament\Forms;
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

class ApprovalRequestResource extends Resource
{
    use HasPreviewModal;
    protected static ?string $model = ApprovalRequest::class;

    protected static ?string $navigationGroup = 'Approval';
    protected static ?string $navigationLabel = 'Approval Request';
    protected static ?string $navigationIcon = 'tabler-report';
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
            ->columns([
                Tables\Columns\TextColumn::make('approvable.request_number')
                    ->label('Request Number'),
                Tables\Columns\TextColumn::make('approvable.department.name')
                    ->searchable()
                    ->label(__('Department')),
                Tables\Columns\TextColumn::make('approvable.customer')
                    ->searchable()
                    ->label(__('Customer / Buyer')),
                Tables\Columns\TextColumn::make('approvable.contract_no')
                    ->searchable()
                    ->label(__('Contract')),
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

                // Tables\Actions\ActionGroup::make([
                ListPreviewAction::make()
                    ->button()
                    ->previewModalData(fn (Model $record) => ['record' => $record->approvable])
                    ->label(__('View'))
                    ->color('info'),
                Tables\Actions\Action::make('order')
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
                    ->action(function (array $data, ApprovalRequest $record): void {
                        $new = new Order();
                        $new->orderable_id = $record->approvable_id;
                        $new->orderable_type = $record->approvable_type;
                        $new->supplier_id = $data['supplier_id'];
                        $new->category_id = $data['category_id'];
                        $new->delivery_date = $data['delivery_date'];
                        $new->payment_term = $data['payment_term'];
                        $new->included_tax = $data['included_tax'];
                        $new->tax_type = $data['tax_type'] ?? null;
                        $new->capex_code = $data['capex_code'] ?? null;
                        $new->comment = $data['comment'];
                        $new->save();
                        $requestItems = $record->approvable->requestItems;
                        foreach ($requestItems as $item) {
                            $new->orderItems()->saveMany([
                                new OrderItem([
                                    'product_id' => $item->product_id,
                                    'quantity' => $item->quantity,
                                    'unit_price' => 0,
                                    'remark' => $item->remark,
                                    'company_id' => auth('ldap')->user()->company->id,
                                ]),
                            ]);
                        }
                    })
                    ->button(),
                Tables\Actions\Action::make('approve')
                    ->hidden(fn (ApprovalRequest $record): bool => $record->status === 'Request Completed')
                    ->action(fn (ApprovalRequest $record) => RequestApproved::dispatch($record))
                    ->button()
                    ->icon('tabler-check')
                    ->color('success'),
                Tables\Actions\Action::make('reject')
                    ->hidden(fn (ApprovalRequest $record): bool => $record->status === 'Request Completed')
                    ->button()
                    ->icon('tabler-clipboard-x')
                    ->color('danger')
                    ->action(fn (ApprovalRequest $record) => $record->approvable->reject()),
                Tables\Actions\Action::make('Comment')

                    ->stickyModalHeader()
                    ->button()
                    ->icon('tabler-message-2')
                    ->modalAlignment(Alignment::Center)
                    ->modalIcon('tabler-message-2')
                    ->modalHeading('Comments')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn (StaticAction $action) => $action->label('Close'))
                    ->modalContent(fn (ApprovalRequest $record): View => view(
                        'filament.purchase.resources.approval-request-resource.pages.comment-approval-request',
                        ['record' => $record],
                    ))
                    // ->extraModalFooterActions(fn (Action $action): array => [
                    //     $action->makeModalSubmitAction('createAnother', arguments: ['another' => true]),
                    // ])
                    // ->form([
                    //     Forms\Components\Textarea::make('body')
                    //         ->columnSpanFull()
                    //         ->label(__('Comment')),
                    // ])
                    // ->action(function (array $data, ApprovalRequest $record,): void {
                    //     $comment = new Comment();
                    //     $comment->body = $data['body'];
                    //     $record->comments()->save($comment);
                    //     // if ($arguments['another'] ?? false) {
                    //     //     //not closing modal
                    //     // }
                    // })
                    ->closeModalByClickingAway(false)


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
