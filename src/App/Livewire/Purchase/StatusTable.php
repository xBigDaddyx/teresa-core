<?php

namespace App\Livewire\Purchase;

use Carbon\Carbon;
use Domain\Purchases\Models\ApprovalRequest;
use Domain\Purchases\Models\ApprovalUser;
use Domain\Purchases\Models\Request;
use Domain\Users\Models\User;
use Livewire\Component;
use Filament\Tables;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class StatusTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    public Request $request;
    public function mount($record)
    {
        $this->request = $record;
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(ApprovalRequest::with('approvalFlow')->where('approvable_id', $this->request->id)->orderBy('created_at', 'DESC'))
            ->columns([
                Tables\Columns\TextColumn::make('approvable.request_number')
                    ->label(__('Number'))
                    ->description(fn (Model $record) => $record->approvalFlow->type),
                Tables\Columns\TextColumn::make('approvalFlow.level')
                    ->label(__('Stage')),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Submited' => 'gray',
                        'Approved' => 'success',
                        'Completed' => 'success',
                        'Processed' => 'warning',
                        'rejected' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->description(fn (Model $record): string => $record->createdBy->approvalUser->first()->level ?? '-')
                    ->label('Processed By'),
                Tables\Columns\TextColumn::make('created_at')
                    ->description(fn (Model $record): string => Carbon::parse($record->created_at)->diffForHumans())
                    ->dateTime()
                    ->label(__('Processed At')),
                Tables\Columns\TextColumn::make('last_status')
                    ->default('-'),
                Tables\Columns\TextColumn::make('user.name')
                    ->description(function (Model $record) {
                        $person = $record->user;
                        if ($person) {
                            if ($person->approvalUser->count() > 1) {
                                return ApprovalUser::where('user_id', $person->id)->where('type', 'PR')->first()->level;
                            } else if ($person->approvalUser->count() === 1) {
                                return $person->approvalUser->first()->level;
                            }
                        }

                        return '-';
                    })
                    ->label(__('Now Waiting')),

            ])
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
        return view('livewire.purchase.status-table');
    }
}
