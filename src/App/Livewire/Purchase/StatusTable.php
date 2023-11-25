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
use Awcodes\FilamentBadgeableColumn\Components\Badge;
use Awcodes\FilamentBadgeableColumn\Components\BadgeableColumn;
use Illuminate\Database\Eloquent\Collection;
use Xbigdaddyx\HarmonyFlow\Models\Approval;

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

        // $collection = new Collection(Approval::where('approvable_id', $this->request->id)->first()->statuses);
        // dd($collection);
        return $table
            ->query(Approval::where('approvable_id', $this->request->id)->first()->statuses->toQuery())
            // ->query(ApprovalRequest::with('approvalFlow')->where('approvable_id', $this->request->id)->orderBy('created_at', 'DESC'))
            ->columns([
                Tables\Columns\TextColumn::make('model.approvable.request_number')
                    ->label(__('Request Number'))
                    ->description(fn (Model $record) => $record->model->type),

                // Tables\Columns\TextColumn::make('approvable.request_number')
                //     ->label(__('Number'))
                //     ->description(fn (Model $record) => $record->approvalFlow->type),
                // BadgeableColumn::make('reason')
                //     ->description(fn (Model $record) => 'Approval request sent : ' . Carbon::parse($record->created_at)->format('d M Y H:i:s'))
                //     ->label('Status')
                //     ->suffixBadges([
                //         Badge::make('status')
                //             ->label(fn (Model $record) => $record->name)
                //             ->color('danger')
                //             ->color(fn (string $state): string => match ($state) {
                //                 'Submited' => 'gray',
                //                 'Approved' => 'success',
                //                 'Completed' => 'success',
                //                 'Processed' => 'warning',
                //                 'rejected' => 'danger',
                //                 default => 'primary',
                //             }),
                //     ]),



                Tables\Columns\TextColumn::make('name')
                    // ->toggleable(isToggledHiddenByDefault: true)
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
                Tables\Columns\TextColumn::make('reason')
                    ->tooltip(fn (Model $record): string => "{$record->author->name}")
                    ->label(__('Description')),

                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->description(fn (Model $record): string => Carbon::parse($record->created_at)->diffForHumans())
                    ->dateTime()
                    ->label(__('Time')),
                // Tables\Columns\TextColumn::make('last_status')
                //     ->toggleable(isToggledHiddenByDefault: true)
                //     ->default('-'),
                // Tables\Columns\TextColumn::make('approvalFlow.level')
                //     ->label(__('Stage')),
                // Tables\Columns\TextColumn::make('user.name')
                //     ->description(function (Model $record) {
                //         $person = $record->user;
                //         if ($person) {
                //             if ($person->approvalUser->count() > 1) {
                //                 return ApprovalUser::where('user_id', $person->id)->where('type', 'PR')->first()->level;
                //             } else if ($person->approvalUser->count() === 1) {
                //                 return $person->approvalUser->first()->level;
                //             }
                //         }

                //         return '-';
                //     })
                //     ->label(__('Now Waiting')),

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
        return view('livewire.purchase.status-table');
    }
}
