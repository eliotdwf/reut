<?php

namespace App\Filament\Resources;

use App\Enums\Permission;
use App\Filament\Resources\AssoResource\Pages;
use App\Filament\Resources\AssoResource\RelationManagers;
use App\Models\Asso;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AssoResource extends Resource
{
    protected static ?string $model = Asso::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(25)
            ->defaultSort('shortname')
            ->columns([
                Tables\Columns\TextColumn::make('login')
                    ->label('Login')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('shortname')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('in_cemetery')
                    ->label('Asso active')
                    ->state(fn(Asso $record) => !$record->in_cemetery)
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/y H:i')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Mis à jour le')
                    ->dateTime('d/m/y H:i')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('parent_id')
                    ->label('Asso Parente')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn($state) => $state ? Asso::find($state)->shortname : 'Aucun'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('in_cemetery')
                    ->label('Au cimetière')
                    ->options([
                        true => 'Oui',
                        false => 'Non',
                    ]),
                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('Asso Parente')
                    ->searchable()
                    ->options(Asso::parentAssos()->pluck('shortname', 'id')),
            ])
            ->actions([
               //
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListAssos::route('/'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasPermission(Permission::VIEW_ASSOS->value);
    }
}
