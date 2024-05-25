<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(100),
                Forms\Components\TextInput::make('password')
                                            ->password()
                                            ->required()
                                            ->minLength(8)
                                            ->hiddenOn('edit'),
                Forms\Components\TextInput::make('email')
                                            ->email()
                                            ->disabledOn('edit')
                                            ->required(),
                Forms\Components\Select::make('role_id')
                                            ->relationship('role','name')
                                            ->options(\App\Models\Role::pluck('name','id'))
                                            ->searchable()
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('name')
                                                                            ->required(),
                                            ])
                                            ->disabled(fn(): bool => !auth()->user()->is_admin),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query){
                return $query->with('branch');
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                                            ->getStateUsing(function (Model $record){
                                                return ucwords($record->name);
                                            })
                                            ->searchable()
                                            ->sortable(),
                Tables\Columns\TextColumn::make('email')
                                            ->searchable()
                                            ->sortable(),
                Tables\Columns\TextColumn::make('role.name')
                                            ->label('Role')
                                            ->sortable(),
                Tables\Columns\SelectColumn::make('branch_id')
                                            ->label('Branch')
                                            ->options(\App\Models\Branch::pluck('name','id')),        
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
