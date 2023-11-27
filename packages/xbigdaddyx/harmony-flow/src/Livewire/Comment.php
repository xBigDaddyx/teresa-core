<?php

namespace Xbigdaddyx\HarmonyFlow\Livewire;

use Carbon\Carbon;



use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Livewire\Component;
use Filament\Tables;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;

use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Support\Enums\FontWeight;


class Comment extends Component implements HasForms, HasActions, HasTable
{
    use InteractsWithTable;
    use InteractsWithActions;
    use InteractsWithForms;
    public ?array $data = [];
    public Model $request;
    public function table(Table $table): Table
    {
        $request = $this->request;

        return $table
            ->query($request->getCommentsByDepartment())
            ->defaultSort('created_at', 'desc')
            // ->query($this->request->comments->toQuery())
            ->columns([

                Tables\Columns\Layout\Split::make([
                    Tables\Columns\ImageColumn::make('user.avatar')
                        ->grow(false)
                        ->circular(),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('user.name')
                            ->description(fn (Model $record): string => $record->user->departments->where('department_id', $record->department_id)->first()->name ?? '-')
                            ->weight(FontWeight::Bold),
                    ])->grow(false),
                    Tables\Columns\Layout\Panel::make([
                        Tables\Columns\Layout\Stack::make([
                            Tables\Columns\TextColumn::make('body')
                                ->icon('tabler-message-2')
                                ->label('Message'),
                            Tables\Columns\TextColumn::make('created_at')
                                ->icon('tabler-clock-edit')
                                ->dateTime(),
                        ]),

                    ]),
                ]),


            ])
            ->filters([
                // ...
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (Model $record): bool => (int)$record->created_by === auth('ldap')->user()->id)
                    ->button()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                // ...
            ])
            ->paginated([3, 5, 10, 'all'])
            ->defaultPaginationPageOption(3);
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('body')
                    ->label(__('Comment'))
                    ->required(),
            ])
            ->statePath('data');
    }
    public function mount($record)
    {
        $this->request = $record;
    }
    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->action(function (array $arguments) {
                $comment = app((config('harmony-flow.models.comments')))::find($arguments['comment']);

                $comment?->delete();
                Notification::make()
                    ->title('Comment Removed')
                    ->danger()
                    ->body('Your comment removed successfully')
                    ->send();
            })
            ->hidden(function (array $arguments) {

                $comment = app((config('harmony-flow.models.comments')))::find($arguments['comment']);
                if ($comment->created_by === auth('ldap')->user()->id) {
                    return false;
                }
                return true;
            });
    }
    public function create()
    {

        $value = $this->form->getState();
        $comment = app((config('harmony-flow.models.comments')));
        $comment->body = $value['body'];
        $this->request->comments()->save($comment);

        $this->data = [];


        Notification::make()
            ->title('Request Commented')
            ->success()
            ->body('Your request ' . $this->request->request_number . ' have been commented by ' . auth()->user()->name . ' "' . $value['body'] . '"')
            ->actions([
                NotificationAction::make('view')
                    ->button()
                    ->url(route('filament.purchase.resources.requests.index', ['tenant' => Filament::getTenant()])),
            ])
            ->sendToDatabase(app((config('harmony-flow.models.users')))::find($this->request->created_by));
    }

    public function render()
    {
        return view('harmony-flow::livewire.comment');
    }
}
