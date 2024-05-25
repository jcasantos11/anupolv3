<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BranchResource\Pages;
use App\Filament\Resources\BranchResource\RelationManagers;
use App\Models\Branch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                                            ->label('Branch Name')
                                            ->required()
                                            ->minLength(2)
                                            ->maxLength(50),
                Forms\Components\TextInput::make('address')
                                            ->label('Branch Address')
                                            ->required()
                                            ->minLength(6),
                Forms\Components\TextInput::make('contact_number')
                                            ->tel()
                                            ->minLength(6)
                                            ->maxLength(12),
                Forms\Components\TextInput::make('email')
                                            ->email()
                                            ->minLength(6),
                Forms\Components\Select::make('client_id')
                                        ->relationship('clients', 'name')
                                        ->label('Owner')
                                        ->options(\App\Models\Client::pluck('name','id'))
                                        ->createOptionForm([
                                                Forms\Components\TextInput::make('name')
                                                                            ->required(),
                                                Forms\Components\TextInput::make('address')
                                                                            ->required()
                                                                            ->maxLength(100),
                                            ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                                        ->searchable()
                                        ->sortable(),
                Tables\Columns\TextColumn::make('address')
                                        ->searchable()
                                        ->sortable(),
                Tables\Columns\TextColumn::make('client.name')
                                        ->label('Owner'),
            ])
            ->modifyQueryUsing(function (Builder $q){
                if(auth()->user()->role?->name == 'owner'){
                    return $q->where('id', auth()->user()->branch_id); 
                }
            })
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
            'index' => Pages\ListBranches::route('/'),
            'create' => Pages\CreateBranch::route('/create'),
            'edit' => Pages\EditBranch::route('/{record}/edit'),
        ];
    }
}
