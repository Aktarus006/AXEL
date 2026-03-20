<?php

namespace App\Filament\Resources;

use App\Enums\Material;
use App\Enums\Status;
use App\Enums\Type;
use App\Filament\Resources\JewelResource\Pages;
use App\Filament\Resources\JewelResource\RelationManagers;
use App\Models\Jewel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Action as FilamentAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class JewelResource extends Resource
{
    protected static ?string $model = Jewel::class;

    protected static ?string $navigationIcon = "heroicon-o-rectangle-stack";

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form->schema(Jewel::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('lifestyle_thumbnail')
                    ->label('Lifestyle')
                    ->circular()
                    ->collection('jewels/lifestyle')
                    ->conversion('thumbnail'),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('packshot_thumbnail')
                    ->label('Packshot')
                    ->circular()
                    ->collection('jewels/packshots')
                    ->conversion('thumbnail'),
                Tables\Columns\TextColumn::make("name")
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("price")
                    ->label('Prix')
                    ->numeric(decimalPlaces: 0, thousandsSeparator: ' ')
                    ->suffix(' €')
                    ->sortable(),
                Tables\Columns\TextColumn::make("material")
                    ->label('Matériaux')
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(fn($state) => collect($state)->map(fn(Material $material) => ucfirst($material->value))->join(', '))
                    ->searchable(),
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
                    ->action(function (Jewel $record): void {
                        $record->online = $record->online === Status::ONLINE ? Status::OFFLINE : Status::ONLINE;
                        $record->save();

                        Notification::make()
                            ->title('Statut mis à jour')
                            ->success()
                            ->send();
                    }),
                Tables\Columns\TextColumn::make("collections.name")
                    ->label('Collections')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make("created_at")
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('collections')
                    ->relationship('collections', 'name')
                    ->multiple()
                    ->preload(),
                SelectFilter::make('online')
                    ->label('Statut')
                    ->options(Status::class),
                SelectFilter::make('material')
                    ->label('Matière')
                    ->options(Material::class)
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) return $query;
                        return $query->whereJsonContains('material', $data['value']);
                    }),
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
            ->defaultSort('created_at', 'desc');
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
            "index" => Pages\ListJewels::route("/"),
            "create" => Pages\CreateJewel::route("/create"),
            "edit" => Pages\EditJewel::route("/{record}/edit"),
        ];
    }
}
