<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CollaborationResource\Pages;
use App\Filament\Resources\CollaborationResource\RelationManagers;
use App\Models\Collaboration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CollaborationResource extends Resource
{
    protected static ?string $model = Collaboration::class;

    protected static ?string $navigationIcon = "heroicon-o-rectangle-stack";

    public static function form(Form $form): Form
    {
        return $form->schema(Collaboration::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name")->searchable(),
                Tables\Columns\TextColumn::make("description")->searchable(),
                Tables\Columns\IconColumn::make("online")->boolean(),
                Tables\Columns\TextColumn::make("creation_date")
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make("collection.name")
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
            "index" => Pages\ListCollaborations::route("/"),
            "create" => Pages\CreateCollaboration::route("/create"),
            "edit" => Pages\EditCollaboration::route("/{record}/edit"),
        ];
    }
}
