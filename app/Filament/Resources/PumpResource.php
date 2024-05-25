<?php

namespace App\Filament\Resources;

use App\Models\Pump;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PumpResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PumpResource\RelationManagers;

class PumpResource extends Resource
{
    protected static ?string $model = Pump::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('branch_id')
                        ->relationship('branch', 'branch')
                        ->required()
                        ->disabledOn('edit')
                        ->options(\App\Models\Branch::pluck('name', 'id')),
                Select::make('product_id')
                        ->relationship('product', 'name')
                        ->required()
                        ->options(\App\Models\Product::pluck('name', 'id')),
                TextInput::make('name')
                            ->required(),
                TextInput::make('type'),
                Select::make('status')
                        ->options([
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                            'repairing' => 'Repairing',
                            'no stock' => 'No Stock',
                        ]),
                DatePicker::make('reset_date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('branch.name')
                                        ->sortable(),
                Tables\Columns\TextColumn::make('name')
                                        ->label('Pump Name')
                                        ->searchable(),
                Tables\Columns\TextColumn::make('product.name')
                                        ->label('Product Name')
                                        ->sortable(),
                Tables\Columns\TextColumn::make('status')
                                        ->badge()
                                        ->color(fn (string $state): string => match ($state) {
                                                    'Active' => 'success',
                                                    'Inactive' => 'danger',
                                                    'Repairing' => 'warning',
                                                    'No Stock' => 'gray',
                                                })
                                        ->getStateUsing(function (Model $record){
                                                return ucwords($record->status);
                                            }),

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
            'index' => Pages\ListPumps::route('/'),
            'create' => Pages\CreatePump::route('/create'),
            'edit' => Pages\EditPump::route('/{record}/edit'),
        ];
    }
}
