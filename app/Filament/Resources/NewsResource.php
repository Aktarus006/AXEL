<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Filament\Resources\NewsResource\RelationManagers;
use App\Models\News;
use App\Enums\Status;
use App\Enums\Material;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = "heroicon-o-newspaper";

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form->schema(News::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                    ->label('Aperçu')
                    ->collection('news/images')
                    ->conversion('thumbnail')
                    ->circular(),
                Tables\Columns\TextColumn::make("title")
                    ->label('Titre')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
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
                    ->action(function (News $record): void {
                        $record->online = $record->online === Status::ONLINE ? Status::OFFLINE : Status::ONLINE;
                        $record->save();

                        Notification::make()
                            ->title('Statut mis à jour')
                            ->success()
                            ->send();
                    }),
                Tables\Columns\TextColumn::make("jewel.name")
                    ->label('Bijou lié')
                    ->badge()
                    ->color('info')
                    ->placeholder('Aucun')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("collection.name")
                    ->label('Série liée')
                    ->badge()
                    ->color('warning')
                    ->placeholder('Aucune')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("creators.last_name")
                    ->label('Créateurs')
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(fn ($record) => $record->creators->map(fn ($c) => "{$c->first_name} {$c->last_name}")->join(', '))
                    ->placeholder('Aucun'),
                Tables\Columns\TextColumn::make("creation_date")
                    ->label('Date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('online')
                    ->label('Statut')
                    ->options(Status::class),
                SelectFilter::make('jewel_id')
                    ->label('Filtrer par bijou')
                    ->relationship('jewel', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('collection_id')
                    ->label('Filtrer par série')
                    ->relationship('collection', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('toggleOnline')
                        ->label('Basculer En Ligne/Hors Ligne')
                        ->icon('heroicon-o-arrow-path')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $records->each(function ($record) {
                                $record->online = $record->online === Status::ONLINE ? Status::OFFLINE : Status::ONLINE;
                                $record->save();
                            });
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('creation_date', 'desc');
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
            "index" => Pages\ListNews::route("/"),
            "create" => Pages\CreateNews::route("/create"),
            "edit" => Pages\EditNews::route("/{record}/edit"),
        ];
    }
}
