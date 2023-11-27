<?php

namespace Xbigdaddyx\HarmonyFlow\Livewire;

use Carbon\Carbon;

use Livewire\Component;
use Filament\Tables;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;


class Status extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    public Model $request;
    public function mount($record)
    {
        $this->request = $record;
    }
    public function table(Table $table): Table
    {
        return $table
            ->query($this->request->approvals->toQuery())
            ->columns([
                Tables\Columns\TextColumn::make('approvable_id')
                    ->formatStateUsing(fn ($state, Model $record): string => app(($record->approvable_type))::find((int)$state)->request_number)
                    ->label(__('Request Number'))
                    ->description(fn (Model $record) => app(($record->approvable_type))::find((int)$record->approvable_id)->note),
                Tables\Columns\TextColumn::make('approval_action')
                    ->label(__('Status'))
                    ->badge()

                    ->color(fn (string $state): string => match ($state) {
                        'Submited' => 'gray',
                        'Approved' => 'success',
                        'Completed' => 'success',
                        'Processed' => 'warning',
                        'rejected' => 'danger',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('approver_name')
                    ->label('Charge'),
                Tables\Columns\TextColumn::make('comment')
                    ->formatStateUsing(function ($state) {
                        dd($state);
                    })
                    ->tooltip(fn (Model $record): string => "{$record->approver_name}")
                    ->label(__('Description')),

                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->description(fn (Model $record): string => Carbon::parse($record->created_at)->diffForHumans())
                    ->dateTime()
                    ->label(__('Time')),
            ])
            ->defaultSort('created_at', 'desc')
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
    public function render()
    {
        return view('harmony-flow::livewire.status');
    }
}
