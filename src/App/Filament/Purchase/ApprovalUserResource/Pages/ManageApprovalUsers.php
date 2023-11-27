<?php

namespace App\Filament\Purchase\ApprovalUserResource\Pages;

use App\Filament\Purchase\Resources\ApprovalUserResource;
use Domain\Purchases\Models\Department;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ManageRecords;
use Filament\Forms;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Builder;

class ManageApprovalUsers extends ManageRecords
{
    protected static string $resource = ApprovalUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->steps([
                    Forms\Components\Wizard\Step::make('Department')
                        ->description('Approval in charge department')
                        ->icon('tabler-briefcase')
                        ->schema([
                            Forms\Components\Grid::make(1)
                                ->schema([

                                    Forms\Components\Select::make('department_id')
                                        ->required()
                                        ->hint('Select department')
                                        ->relationship('department', 'name', modifyQueryUsing: fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant()))

                                        ->label(__('Department'))
                                        ->searchable()
                                        ->preload()
                                        ->live(),
                                ]),


                        ]),
                    Forms\Components\Wizard\Step::make('User')
                        ->description('Approval in charge person')
                        ->icon('tabler-user')
                        ->schema([
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    Forms\Components\Select::make('user_id')
                                        ->required()
                                        ->hint('Select user')
                                        ->relationship('user', 'name', modifyQueryUsing: function (Builder $query, Get  $get) {

                                            $query->whereHas('purchaseDepartments', function (Builder $q) use ($get) {
                                                $department = Department::find($get('department_id'));
                                                if ($department) {
                                                    $q->where('name', $department->name);
                                                }
                                            });
                                        })
                                        ->searchable()

                                        ->label(__('User')),
                                    Forms\Components\Select::make('level')
                                        ->required()
                                        ->options([
                                            'User' => 'User',
                                            'Supervisor' => 'Supervisor',
                                            'Manager' => 'Manager',
                                            'Purchasing' => 'Purchasing',
                                            'Purchasing Manager' => 'Purchasing Manager',
                                            'Finance Controller' => 'Finance Controller',
                                            'CFO' => 'CFO',
                                            'General Manager' => 'General Manager',
                                            'Country Head' => 'Country Head',
                                        ])
                                        ->label(__('Level')),



                                ]),


                        ]),
                    Forms\Components\Wizard\Step::make('Type')
                        ->description('Approval in charge type')
                        ->icon('tabler-clipboard')
                        ->schema([
                            Forms\Components\Grid::make(1)
                                ->schema([

                                    Forms\Components\Select::make('type')
                                        ->options([
                                            'PR' => 'PR',
                                            'PO' => 'PO'
                                        ])
                                        ->label(__('Approval Type')),

                                ]),


                        ]),

                ])
        ];
    }
}
