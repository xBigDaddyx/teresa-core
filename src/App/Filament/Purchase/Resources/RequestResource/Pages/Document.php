<?php

namespace App\Filament\Purchase\Resources\RequestResource\Pages;

use App\Filament\Purchase\Resources\RequestResource;
use Domain\Purchases\Models\Request;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\View\View;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Illuminate\Database\Eloquent\Model;

class Document extends Page implements HasForms, HasInfolists
{
    use InteractsWithInfolists;
    use InteractsWithForms;

    public $record;
    public $approvals;
    public $flows;

    protected static string $resource = RequestResource::class;
    protected static ?string $title = 'Purchase Request Form';
    protected ?string $heading = 'Purchase Request Form';
    protected ?string $subheading = 'Custom Page Subheading';
    protected static string $view = 'filament.purchase.resources.request-resource.pages.document';

    public function mount($record)
    {
        $this->record = Request::findOrFail($record);
        $this->approvals = $this->record->approvals;
        $this->flows = $this->record->approvalFlowSteps();
    }
    public function approvalInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema([

                Infolists\Components\TextEntry::make('approvals.approver_name'),


            ]);
    }
    public function getFooter(): ?View
    {
        return view('filament.purchase.resources.request-resource.pages.footer', ['record' => $this->record, 'flows' => $this->flows, 'approvals' => $this->approvals]);
    }
}
