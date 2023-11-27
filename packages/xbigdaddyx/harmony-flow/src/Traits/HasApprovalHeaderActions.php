<?php

namespace Xbigdaddyx\HarmonyFlow\Traits;


use Xbigdaddyx\HarmonyFlow\Forms\Actions\ApproveAction;
use Xbigdaddyx\HarmonyFlow\Forms\Actions\DiscardAction;
use Xbigdaddyx\HarmonyFlow\Forms\Actions\RejectAction;
use Xbigdaddyx\HarmonyFlow\Forms\Actions\SubmitAction;
use Xbigdaddyx\HarmonyFlow\Models\ApprovableModel;
use Exception;
use Filament\Actions\Action;

trait HasApprovalHeaderActions
{

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getApprovalHeaderActions()
        ];
    }

    protected function getApprovalHeaderActions(): array
    {
        return [
            ApproveAction::make(),
            RejectAction::make(),
            DiscardAction::make(),
            SubmitAction::make(),
            $this->getOnCompletionAction()
                ->visible(fn (ApprovableModel $record) => $record->isApprovalCompleted())
        ];
    }

    /**
     * Get the completion action
     *
     * @return Filament\Actions\Action
     * @throws Exception
     */
    protected function getOnCompletionAction(): Action
    {
        throw new Exception("Completion action not defined");
    }
}
