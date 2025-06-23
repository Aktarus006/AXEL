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
                Tables\Columns\SpatieMediaLibraryImageColumn::make('avatar')
                    ->label('Photo')
                    ->collection('creators/profile')
                    ->conversion('avatar-thumbnail')
                    ->extraImgAttributes(fn($record) => [
                        'data-hover-src' => $record->getFirstMediaUrl('creators/profile', 'avatar-thumbnail', 'avatar_hover'),
                        'onmouseover' => "this.src = this.getAttribute('data-hover-src')",
                        'onmouseout' => "this.src = '" . $record->getFirstMediaUrl('creators/profile', 'avatar-thumbnail') . "'",
                    ]),
                Tables\Columns\TextColumn::make("first_name")
                    ->label('Prénom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("last_name")
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("job_title")
                    ->label('Titre du poste')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("date_of_birth")
                    ->label('Date de naissance')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make("online")
                    ->label('En ligne')
                    ->boolean(),
                Tables\Columns\TextColumn::make("created_at")
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make("updated_at")
                    ->label('Modifié le')
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
