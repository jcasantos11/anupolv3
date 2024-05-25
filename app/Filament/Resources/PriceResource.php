<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Price;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PriceResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PriceResource\RelationManagers;

class PriceResource extends Resource
{
    protected static ?string $model = Price::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date_recorded')
                                            ->label('Date')
                                            ->required(),
                Forms\Components\Select::make('product_id')
                                            ->relationship('product','name')
                                            ->options(\App\Models\Product::pluck('name','id'))
                                            ->searchable()
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('name')
                                                                            ->required(),
                                            ]),
                Forms\Components\TextInput::make('cost')
                                            ->prefix('Php')
                                            ->placeholder('00.00')
                                            ->numeric()
                                            ->inputMode('decimal')
                                            ->required(),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query){
                return $query->with('product');
            })
            ->columns([
                Tables\Columns\TextColumn::make('date_recorded')
                                            ->label('Date')
                                            ->sortable(),
                Tables\Columns\TextColumn::make('product.name')
                                            ->sortable(),
                Tables\Columns\TextColumn::make('cost')
                                            ->money('PHP'),
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
            'index' => Pages\ListPrices::route('/'),
            'create' => Pages\CreatePrice::route('/create'),
            'edit' => Pages\EditPrice::route('/{record}/edit'),
        ];
    }
}
