<?php

namespace Xbigdaddyx\HarmonyFlow\Traits;

use Xbigdaddyx\HarmonyFlow\Forms\Actions\ApproveAction;
use Xbigdaddyx\HarmonyFlow\Forms\Actions\DiscardAction;
use Xbigdaddyx\HarmonyFlow\Forms\Actions\RejectAction;
use Xbigdaddyx\HarmonyFlow\Forms\Actions\SubmitAction;
use Filament\Actions\Action;

trait HasApprovalFormActions
{

    protected function getFormActions(): array
    {
        return [
            ...$this->formActions(),
            ...parent::getFormActions(),
        ];
    }

    protected function formActions(): array
    {
        return [
            ApproveAction::make(),
            RejectAction::make(),
            DiscardAction::make(),
            SubmitAction::make()
        ];
    }
}
