<?php

namespace App\Filament\Resources;

use App\Enums\Material;
use App\Enums\Status;
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

class JewelResource extends Resource
{
    protected static ?string $model = Jewel::class;

    protected static ?string $navigationIcon = "heroicon-o-rectangle-stack";

    public static function form(Form $form): Form
    {
        return $form->schema(Jewel::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('media')
                    ->label('Images')
                    ->circular()
                    ->stacked()
                    ->limit(2)
                    ->state(function ($record): array {
                        if (!$record) return [];

                        $images = [];
                        if ($packshot = $record->getFirstMedia('jewels/packshots')) {
                            $images[] = $packshot->getUrl('thumbnail');
                        }
                        if ($lifestyle = $record->getFirstMedia('jewels/lifestyle')) {
                            $images[] = $lifestyle->getUrl('thumbnail');
                        }
                        return $images;
                    }),
                Tables\Columns\TextColumn::make("name")
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("price")
                    ->label('Prix')
                    ->money('eur')
                    ->sortable(),
                Tables\Columns\TextColumn::make("material")
                    ->label('Matériaux')
                    ->badge()
                    ->color('warning')
                    ->listWithLineBreaks()
                    ->limitList(2)
                    ->expandableLimitedList()
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

                        redirect(request()->header('Referer'));
                    }),
                Tables\Columns\TextColumn::make("creation_date")
                    ->label('Date de création')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make("collections.name")
                    ->label('Collections')
                    ->badge()
                    ->sortable()
                    ->searchable(),
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
            ->actions([
                Action::make('switchCollection')
                    ->label('Changer de collection')
                    ->icon('heroicon-o-arrow-path')
                    ->form([
                        Forms\Components\Select::make('collections')
                            ->label('Collections')
                            ->multiple()
                            ->relationship('collections', 'name')
                            ->required()
                            ->preload()
                            ->default(fn(Jewel $record) => $record->collections->pluck('id')->toArray())
                            ->searchable(),
                    ])
                    ->action(function (Jewel $record, array $data): void {
                        if (isset($data['collections'])) {
                            $record->collections()->sync($data['collections']);
                        }
                    })
                    ->successNotificationTitle('Collections modifiées'),
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
            "index" => Pages\ListJewels::route("/"),
            "create" => Pages\CreateJewel::route("/create"),
            "edit" => Pages\EditJewel::route("/{record}/edit"),
        ];
    }
}
