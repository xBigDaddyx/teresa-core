<?php

namespace App\Filament\Purchase\Resources\ApprovalRequestResource\Pages;

use App\Filament\Purchase\Resources\ApprovalRequestResource;
use Filament\Resources\Pages\Page;

class CommentApprovalRequest extends Page
{
    protected static string $resource = ApprovalRequestResource::class;

    protected static string $view = 'filament.purchase.resources.approval-request-resource.pages.comment-approval-request';
}
