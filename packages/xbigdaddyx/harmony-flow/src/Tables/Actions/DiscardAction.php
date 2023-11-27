<?php

namespace Xbigdaddyx\HarmonyFlow\Tables\Actions;

use Closure;
use Xbigdaddyx\HarmonyFlow\Models\ApprovableModel;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DiscardAction extends Action
{

    public static function getDefaultName(): ?string
    {
        return 'Discard';
    }


    protected function setUp(): void
    {
        parent::setUp();

        $this->color('danger')
            ->action('Discard')
            ->visible(
                fn (Model $record) =>
                $record->canBeApprovedBy(Auth::guard(config('harmony-flow.guard'))->user()) &&
                    $record->isRejected()
            )
            ->requiresConfirmation();
    }


    public function action(Closure | string | null $action): static
    {
        if ($action !== 'Discard') {
            throw new \Exception('You\'re unable to override the action for this plugin');
        }

        $this->action = $this->discardModel();

        return $this;
    }


    /**
     * Discard data function.
     *
     */
    private function discardModel(): Closure
    {
        return function (array $data, ApprovableModel $record): bool {
            $record->discard(null, Auth::guard(config('harmony-flow.guard'))->user());
            Notification::make()
                ->title('Discarded successfully')
                ->success()
                ->send();

            return true;
        };
    }
}
