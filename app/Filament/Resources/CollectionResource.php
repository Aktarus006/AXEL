<?php

namespace App\Filament\Resources;

use App\Enums\Status;
use App\Filament\Resources\CollectionResource\Pages;
use App\Filament\Resources\CollectionResource\RelationManagers;
use App\Models\Collection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TagsColumn;

class CollectionResource extends Resource
{
    protected static ?string $model = Collection::class;

    protected static ?string $navigationIcon = "heroicon-o-rectangle-stack";

    public static function form(Form $form): Form
    {
        return $form->schema(Collection::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name")
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make("online")
                    ->label('En ligne')
                    ->icon(fn(Status $state): string => match ($state) {
                        Status::ONLINE => 'heroicon-s-check-circle',
                        Status::OFFLINE => 'heroicon-s-x-circle',
                    })
                    ->color(fn(Status $state): string => match ($state) {
                        Status::ONLINE => 'success',
                        Status::OFFLINE => 'danger',
                    })
                    ->sortable()
                    ->alignCenter()
                    ->action(function (Collection $record): void {
                        $record->online = $record->online === Status::ONLINE ? Status::OFFLINE : Status::ONLINE;
                        $record->save();

                        Notification::make()
                            ->title('Statut mis à jour')
                            ->success()
                            ->send();

                        redirect(request()->header('Referer'));
                    }),
                Tables\Columns\TextColumn::make("jewels_count")
                    ->label('Nombre de bijoux')
                    ->counts('jewels')
                    ->sortable(),
                Tables\Columns\TextColumn::make("creation_date")
                    ->label('Date de création')
                    ->date()
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
            "index" => Pages\ListCollections::route("/"),
            "create" => Pages\CreateCollection::route("/create"),
            "edit" => Pages\EditCollection::route("/{record}/edit"),
        ];
    }
}
