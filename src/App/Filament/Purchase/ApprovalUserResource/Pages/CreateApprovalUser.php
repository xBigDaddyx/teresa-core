<?php

namespace App\Filament\Purchase\ApprovalUserResource\Pages;

use App\Filament\Purchase\Resources\ApprovalUserResource;
use Domain\Purchases\Models\Department;
use Domain\Users\Models\User;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;

class CreateApprovalUser extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;
    protected static string $resource = ApprovalUserResource::class;
    protected function getSteps(): array
    {
        return [
            Forms\Components\Wizard\Step::make('Approval In Charge')
                ->description('Information of approval in charge')
                ->icon('tabler-forms')
                ->schema([
                    Forms\Components\Grid::make(3)
                        ->schema([

                            Forms\Components\Select::make('department_id')
                                ->required()
                                ->hint('Select department')
                                ->relationship('department', 'name', modifyQueryUsing: fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant()))

                                ->label(__('Department'))
                                ->searchable()
                                ->preload()
                                ->live(),
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
                                ->label(__('Approval Level')),

                            Forms\Components\Select::make('type')
                                ->options([
                                    'PR' => 'PR',
                                    'PO' => 'PO'
                                ])
                                ->label(__('Approval Type')),

                        ]),


                ]),



        ];
    }
}
