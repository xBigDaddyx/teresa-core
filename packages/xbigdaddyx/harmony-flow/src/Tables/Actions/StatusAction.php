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

class StatusAction extends Action
{

    public static function getDefaultName(): ?string
    {
        return 'Status';
    }


    protected function setUp(): void
    {
        parent::setUp();

        $this->icon('tabler-status-change')
            ->color('success')
            ->visible(fn (Model $record) => $record->isSubmitted()  && auth('ldap')->user()->hasRole(['purchase-user', 'department-user', 'super-admin']))
            ->modalAlignment(Alignment::Center)
            ->modalIcon('tabler-status-change')
            ->modalHeading('Approval Status')
            ->modalDescription('Below is all information we hold about last approval records for this request')
            ->modalSubmitAction(false)
            ->modalCancelAction(fn (StaticAction $action) => $action->label('Close'))
            ->modalContent(fn (Model $record): View => view(
                'harmony-flow::filament.status-modal',
                ['record' => $record],
            ));
    }
}
