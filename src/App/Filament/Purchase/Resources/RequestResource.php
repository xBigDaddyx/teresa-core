<?php

namespace App\Filament\Purchase\Resources;

use App\Events\RequestSubmited;
use App\Filament\Purchase\Resources\RequestResource\Pages;
use App\Filament\Purchase\Resources\RequestResource\RelationManagers;
use App\Filament\Purchase\Resources\RequestResource\RelationManagers\ApprovalHistoriesRelationManager;
use App\Jobs\ProcessApproval;
use App\Tables\Columns\ProductColumn;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Domain\Purchases\Models\Product;
use Domain\Purchases\Models\Request;
use Domain\Users\Models\User;
use Filament\Actions\StaticAction;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Pboivin\FilamentPeek\Tables\Actions\ListPreviewAction;
use Xbigdaddyx\HarmonyFlow\Jobs\CreateApprovalHistories;
use Xbigdaddyx\HarmonyFlow\Jobs\CreateApprovalRecord;
use Xbigdaddyx\HarmonyFlow\Jobs\UpdateApprovableModel;
use Xbigdaddyx\HarmonyFlow\Models\Approval;

class RequestResource extends Resource
{
    protected static ?string $model = Request::class;

    protected static ?string $navigationGroup = 'Purchase';
    protected static ?string $navigationLabel = 'My Requests';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->label(__('Category'))
                            ->live(),
                        Forms\Components\TextInput::make('customer')
                            ->label(__('Customer / Buyer')),
                        Forms\Components\TextInput::make('contract_no')
                            ->label(__('Contract')),
                        Forms\Components\Textarea::make('note')->columnSpanFull(),
                    ])->columns(3),
                Forms\Components\Section::make('Items')
                    ->schema([
                        Forms\Components\Repeater::make('requestItems')
                            ->label('Requested')
                            ->relationship()
                            ->columns(6)
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->relationship('product', 'name', modifyQueryUsing: function (Builder $query, Get  $get) {
                                        $query->whereHas('category', function (Builder $b) use ($get) {
                                            $b->where('category_id', $get('../../category_id'))->orWhere('category_id', '3');
                                        });
                                    })
                                    ->getOptionLabelFromRecordUsing(function (Model $record) {
                                        if (count($record->specification) > 0) {
                                            $collection = collect($record->specification);
                                            $value = $collection->implode('value', ' ');
                                        } else {
                                            $value = null;
                                        }

                                        return '<span class="font-bold text-primary-500">' . $record->product_number . '</span> - ' . $record->name . ' ' . $value . ' - ' . $record->unit->name;
                                    })
                                    ->searchDebounce(500)
                                    ->searchable(['name'])
                                    ->label(__('Product'))
                                    ->prefixIcon('tabler-paper-bag')
                                    ->allowHtml()
                                    ->prefixIconColor('primary')
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('quantity')

                                    ->prefixIcon('tabler-calculator')
                                    ->prefixIconColor('primary')
                                    ->required()
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->maxValue(99),
                                Forms\Components\DatePicker::make('delivery_date')
                                    ->suffixIconColor('primary')
                                    ->required(),
                                Forms\Components\TextInput::make('stock')
                                    ->prefixIcon('tabler-calculator')
                                    ->prefixIconColor('primary')
                                    ->default(0)
                                    ->minValue(0)
                                    ->maxValue(99)
                                    ->numeric(),
                                Forms\Components\TextInput::make('style_no'),
                                Forms\Components\Textarea::make('remark')->columnSpanFull(),
                            ])->columnSpanFull()->collapsible()->itemLabel(function (array $state) {
                                $product = Product::with('unit')->find($state['product_id']);
                                if ($product) {
                                    if (count($product->specification) > 0) {
                                        $collection = collect($product->specification);
                                        $value = $collection->implode('value', ' ');
                                    } else {
                                        $value = null;
                                    }
                                    return $product->product_number . ' - ' . $product->name . ' ' . $value . ' - ' . $state['quantity'] . ' ' . $product->unit->name;
                                }
                                return null;
                            }),
                    ]),


            ]);
    }

    public static function table(Table $table): Table
    {
        // dd(Request::find(3)->getNextPerson());
        //dd(Filament::getTenant()->short_name);
        // $req = Request::find(9)->getNextApproval();
        // dd($req);
        return $table
            // ->poll('5s')

            ->columns([
                // Tables\Columns\SpatieMediaLibraryImageColumn::make('requestItems.')
                //     ->stacked()
                //     ->collection('products')->conversion('thumb'),
                Tables\Columns\TextColumn::make('request_number')
                    ->searchable()
                    ->label(__('Request No')),
                Tables\Columns\TextColumn::make('category.name')
                    ->badge()
                    ->label('Category'),
                Tables\Columns\TextColumn::make('department.name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->label(__('Department')),
                Tables\Columns\TextColumn::make('customer')
                    ->searchable()
                    ->label(__('Customer / Buyer')),
                Tables\Columns\TextColumn::make('contract_no')
                    ->searchable()
                    ->label(__('Contract')),

                ProductColumn::make('requestItems')
                    ->color('info')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(1)
                    ->label(__('Items')),
                Tables\Columns\TextColumn::make('note')
                    ->limit(30)
                    ->label(__('Note')),
                // Tables\Columns\TextColumn::make('request_items_count')->counts('requestItems')
                //     ->badge()
                //     ->label(__('Items')),
                Tables\Columns\TextColumn::make('created_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->dateTime()
                    ->label(__('Created At')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    ListPreviewAction::make()
                        ->label('View')

                        ->color('info'),
                    Tables\Actions\Action::make('status')

                        ->icon('tabler-status-change')
                        ->color('success')
                        ->visible(fn (Request $record) => $record->is_submited === true || $record->is_processed === true)
                        ->action(fn (Request $record) => $record->advance())
                        ->modalAlignment(Alignment::Center)
                        ->modalIcon('tabler-status-change')
                        ->modalHeading('Approval Status')
                        ->modalDescription('Below is all information we hold about last approval records for this request')
                        ->modalSubmitAction(false)
                        ->modalCancelAction(fn (StaticAction $action) => $action->label('Close'))
                        ->modalContent(fn (Request $record): View => view(
                            'filament.purchase.resources.request-resource.pages.request-status',
                            ['record' => $record],
                        )),
                    Tables\Actions\Action::make('submit')

                        ->icon('tabler-file-export')
                        ->color('success')
                        ->visible(fn (Request $record) => $record->is_submited === false)
                        ->action(function (Request $record) {
                            $record->is_submited = true;
                            $record->setStatus('submitted', 'Submitted by ' . auth('ldap')->user()->name);
                            $record->save();
                            dispatch(new CreateApprovalRecord($record, 'PR', auth('ldap')->user()));
                        }),
                    // ->action(fn (Request $record) => RequestSubmited::dispatch($record, 'PR', auth('ldap')->user())),
                    // Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()

                        ->color('primary'),
                    Tables\Actions\Action::make('Comment')

                        ->stickyModalHeader()

                        ->outlined()
                        ->icon('tabler-message-2')
                        ->badge(fn (Model $record) => $record->comments->count())
                        ->badgeColor('info')
                        ->modalAlignment(Alignment::Center)
                        ->modalIcon('tabler-message-2')
                        ->modalHeading('Comments')
                        ->modalSubmitAction(false)
                        ->modalCancelAction(fn (StaticAction $action) => $action->label('Close'))
                        ->modalContent(fn (Model $record): View => view(
                            'filament.purchase.resources.approval-request-resource.pages.comment-approval-request',
                            ['record' => $record],
                        ))
                        ->closeModalByClickingAway(false)
                ]),



            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ApprovalHistoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRequests::route('/'),
            'create' => Pages\CreateRequest::route('/create'),
            'status' => Pages\RequestStatus::route('/{record}/status'),
            // 'view' => Pages\ViewRequest::route('/{record}'),
            'edit' => Pages\EditRequest::route('/{record}/edit'),
            // 'document' => Pages\RequestDocument::route('/{record}/doc'),
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
