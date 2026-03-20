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

    protected static ?string $navigationIcon = "heroicon-o-rectangle-stack";

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form->schema(News::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("title")
                    ->label('Titre')
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
                    ->action(function (News $record): void {
                        $record->online = $record->online === Status::ONLINE ? Status::OFFLINE : Status::ONLINE;
                        $record->save();

                        Notification::make()
                            ->title('Statut mis à jour')
                            ->success()
                            ->send();
                    }),
                Tables\Columns\TextColumn::make("creation_date")
                    ->label('Date de création')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make("created_at")
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('online')
                    ->label('Statut')
                    ->options(Status::class),
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
