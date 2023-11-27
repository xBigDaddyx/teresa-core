<?php

namespace Xbigdaddyx\HarmonyFlow\Tables\Actions;

use Closure;
use Filament\Actions\StaticAction;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;

class CommentAction extends Action
{

    public static function getDefaultName(): ?string
    {
        return 'Comment';
    }


    protected function setUp(): void
    {
        parent::setUp();

        $this->stickyModalHeader()
            ->outlined()
            ->icon('tabler-message-2')
            ->visible(fn (Model $record) => $record->isSubmitted())
            ->badge(fn (Model $record) => $record->comments->count())
            ->badgeColor('info')
            ->modalAlignment(Alignment::Center)
            ->modalIcon('tabler-message-2')
            ->modalHeading('Comments')
            ->modalSubmitAction(false)
            ->modalCancelAction(fn (StaticAction $action) => $action->label('Close'))
            ->modalContent(fn (Model $record): View => view(
                'harmony-flow::filament.comment-modal',
                ['record' => $record],
            ))
            ->closeModalByClickingAway(false);
    }
}
