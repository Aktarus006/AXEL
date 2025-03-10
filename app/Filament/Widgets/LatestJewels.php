<?php

namespace App\Filament\Widgets;

use App\Models\Jewel;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestJewels extends BaseWidget
{
    protected int $limit = 5;
    protected static ?string $heading = 'Derniers bijoux';
    protected static ?int $sort = 2;
    protected static ?int $columns = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Jewel::query()
                    ->latest()
                    ->limit($this->limit)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('media')
                    ->label('')
                    ->circular()
                    ->state(function ($record): array {
                        if (!$record) return [];
                        return $record->getFirstMedia('jewels/packshots')
                            ? [$record->getFirstMedia('jewels/packshots')->getUrl('thumbnail')]
                            : [];
                    }),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('price')
                    ->label('Prix')
                    ->money('eur'),
                Tables\Columns\IconColumn::make('online')
                    ->label('Statut')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ajouté le')
                    ->dateTime()
                    ->sortable(),
            ]);
    }
}
