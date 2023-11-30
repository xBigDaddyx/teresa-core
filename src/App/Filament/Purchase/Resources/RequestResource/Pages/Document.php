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
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Table;
use FIlament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;

class Document extends Page implements HasForms, HasInfolists, HasTable
{
    use InteractsWithInfolists;
    use InteractsWithForms;
    use InteractsWithTable;

    public $record;
    public $approvals;
    public $flows;

    protected static string $resource = RequestResource::class;
    protected static ?string $title = 'Purchase Request Form';
    protected ?string $heading = 'Purchase Request Form';

    protected static string $view = 'filament.purchase.resources.request-resource.pages.document';
    public function getSubheading(): ?string
    {
        return __($this->record->request_number);
    }
    public function mount($record)
    {
        $this->record = Request::findOrFail($record);
        $this->approvals = $this->record->approvals;
        $this->flows = $this->record->approvalFlowSteps();
    }
    public function table(Table $table): Table
    {
        return $table
            ->query($this->record->requestItems->toQuery())
            ->columns([
                Tables\Columns\TextColumn::make('product')
                    ->html()
                    ->formatStateUsing(function (string $state, Model $record) {
                        if (count($record->product->specification) > 0) {
                            $collection = collect($record->product->specification);
                            $value = $collection->implode('value', ' ');
                        } else {
                            $value = null;
                        }

                        return '<span class="font-bold text-primary-500">' . $record->product->product_number . '</span> - ' . $record->product->name . ' ' . $value;
                    }),
                Tables\Columns\TextColumn::make('quantity')
                    ->summarize(Sum::make()->label('Total')),
                Tables\Columns\TextColumn::make('product.unit.name')
                    ->label('Unit'),
                Tables\Columns\TextColumn::make('delivery_date')
                    ->label('Delivery Date')
                    ->dateTime("d M Y"),
                Tables\Columns\TextColumn::make('remark')
                    ->label('Remark'),
            ])

            ->emptyStateHeading('No items yet')
            ->emptyStateDescription('Once you create your first request, items will appear here.')
            ->paginated(false)
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }
    public function requestInfolist(Infolist $infolist): Infolist
    {

        return $infolist
            ->record($this->record)
            ->schema([
                Infolists\Components\Split::make([
                    Infolists\Components\Section::make([

                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Date'),
                        Infolists\Components\TextEntry::make('department.name')
                            ->label('Department'),
                    ]),
                    Infolists\Components\Section::make([

                        Infolists\Components\TextEntry::make('category.name')
                            ->badge()
                            ->label('Purchase Category')
                    ])
                ]),







            ]);
    }
    public function getFooter(): ?View
    {
        return view('filament.purchase.resources.request-resource.pages.footer', ['record' => $this->record, 'flows' => $this->flows, 'approvals' => $this->approvals]);
    }
}
