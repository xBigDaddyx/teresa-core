<?php

namespace App\Filament\Purchase\Resources;

use App\Events\RequestApproved;
use App\Filament\Purchase\Resources\ApprovalRequestResource\Pages;
use App\Filament\Purchase\Resources\ApprovalRequestResource\RelationManagers;
use App\Filament\Purchase\Resources\ApprovalRequestResource\RelationManagers\CommentsRelationManager;
use Domain\Purchases\Models\ApprovalRequest;
use Domain\Purchases\Models\Comment;
use Filament\Actions\StaticAction;
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

class ApprovalRequestResource extends Resource
{
    use HasPreviewModal;
    protected static ?string $model = ApprovalRequest::class;

    protected static ?string $navigationGroup = 'Approval';
    protected static ?string $navigationLabel = 'Approval Request';
    protected static ?string $navigationIcon = 'tabler-report';
    public static function getNavigationBadge(): ?string
    {

        return static::getModel()::whereBelongsTo(auth()->user())->count();
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
                if (auth()->user()->hasRole('super-admin')) {
                    return $query;
                }
                return $query->whereBelongsTo(auth()->user());
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
                Tables\Actions\Action::make('approve')
                    ->action(fn (ApprovalRequest $record) => RequestApproved::dispatch($record))
                    ->button()
                    ->icon('tabler-check')
                    ->color('success'),
                Tables\Actions\Action::make('reject')
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
