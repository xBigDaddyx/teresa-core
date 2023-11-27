<?php

namespace App\Filament\Purchase;

use App\Filament\Purchase\Resources\ApprovalUserResource\Pages;
use App\Filament\Purchase\Resources\ApprovalUserResource\RelationManagers;
use Domain\Purchases\Models\ApprovalUser;
use Domain\Purchases\Models\Department;
use Domain\Users\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ApprovalUserResource extends Resource
{
    protected static ?string $model = ApprovalUser::class;

    protected static ?string $navigationGroup = 'Approval';
    protected static ?string $navigationLabel = 'Approval User';
    protected static ?string $navigationIcon = 'tabler-user-edit';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        // dd(User::where('email', 'agustina.wahyu@hoplun.com')->first()->approvalUser);
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('Name')),
                Tables\Columns\TextColumn::make('department.name')
                    ->label(__('Department')),
                Tables\Columns\TextColumn::make('level')
                    ->label(__('Level')),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('Type')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
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

                    ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageApprovalUsers::route('/'),
            // 'create' => Pages\CreateApprovalUser::route('/create'),
            // 'edit' => Pages\EditApprovalUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
