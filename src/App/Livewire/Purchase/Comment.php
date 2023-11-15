<?php

namespace App\Livewire\Purchase;

use Carbon\Carbon;
use Domain\Purchases\Models\ApprovalRequest;
use Domain\Purchases\Models\ApprovalUser;
use Domain\Purchases\Models\Comment as ModelsComment;
use Domain\Purchases\Models\Request;
use Domain\Users\Models\User;
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
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;

class Comment extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;
    public ?array $data = [];
    public ApprovalRequest $request;

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
                $comment = ModelsComment::find($arguments['comment']);

                $comment?->delete();
                Notification::make()
                    ->title('Comment Removed')
                    ->danger()
                    ->body('Your comment removed successfully')
                    ->send();
            });
    }
    public function create()
    {

        $value = $this->form->getState();
        $comment = new ModelsComment();
        $comment->body = $value['body'];
        $this->request->comments()->save($comment);

        $this->data = [];

        $recipient = auth()->user();
        $recipient->notify(
            Notification::make()
                ->title('Request Commented')
                ->success()
                ->body('Your request have been commented by' . auth()->user()->name)
                ->toDatabase()
        );
    }

    public function render()
    {
        return view('livewire.purchase.comment');
    }
}
