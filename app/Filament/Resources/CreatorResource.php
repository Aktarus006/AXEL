<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CreatorResource\Pages;
use App\Filament\Resources\CreatorResource\RelationManagers;
use App\Models\Creator;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CreatorResource extends Resource
{
    protected static ?string $model = Creator::class;

    protected static ?string $navigationIcon = "heroicon-o-rectangle-stack";

    public static function form(Form $form): Form
    {
        return $form->schema(Creator::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("first_name")->searchable(),
                Tables\Columns\TextColumn::make("last_name")->searchable(),
                Tables\Columns\TextColumn::make("date_of_birth")
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make("description")->searchable(),
                Tables\Columns\IconColumn::make("online")->boolean(),
                Tables\Columns\TextColumn::make("collaboration_id")
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make("created_at")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make("updated_at")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([Tables\Actions\EditAction::make()])
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
            "index" => Pages\ListCreators::route("/"),
            "create" => Pages\CreateCreator::route("/create"),
            "edit" => Pages\EditCreator::route("/{record}/edit"),
        ];
    }
}
