<?php

namespace App\Filament\Resources\RoomResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccessibleTimeRelationManager extends RelationManager
{
    protected static string $relationship = 'accessibleTimes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('weekday')
                    ->label('Jour de la semaine')
                    ->dehydrated()
                    ->options([
                        'Monday'    => 'Monday',
                        'Tuesday'   => 'Tuesday',
                        'Wednesday' => 'Wednesday',
                        'Thursday'  => 'Thursday',
                        'Friday'    => 'Friday',
                        'Saturday'  => 'Saturday',
                        'Sunday'    => 'Sunday',
                    ])
                    ->disabled(),
                Forms\Components\Placeholder::make('')
                    ->content('Laisser vide les deux champs (--:--) si la salle n\'est pas accessible ce jour-là')
                    ->columnSpanFull(),
                Forms\Components\TimePicker::make('opens_at')
                    ->label('Ouverture')
                    ->nullable()
                    ->columnSpan(1)
                    ->seconds(false),
                Forms\Components\TimePicker::make('closes_at')
                    ->label('Fermeture')
                    ->nullable()
                    ->columnSpan(1)
                    ->seconds(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('weekday')
            ->columns([
                Tables\Columns\TextColumn::make('weekday'),
                Tables\Columns\TextColumn::make('opens_at')
                    ->label('Ouverture')
                    ->dateTime('H:i'),
                Tables\Columns\TextColumn::make('closes_at')
                    ->label('Fermeture')
                    ->dateTime('H:i'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }
}
