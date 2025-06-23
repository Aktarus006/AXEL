<?php
namespace App\Filament\Resources;

use App\Filament\Resources\SlideResource\Pages;
use App\Models\Slide;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class SlideResource extends Resource
{
    protected static ?string $model = Slide::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $label = 'Slide';
    protected static ?string $pluralLabel = 'Slides';

    public static function form(Form $form): Form
    {
        
        return $form->schema(Slide::getForm());

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
                Tables\Columns\ImageColumn::make('image')->getStateUsing(fn($record) => $record->getFirstMediaUrl('images', 'thumb')),
                Tables\Columns\TextColumn::make('button_text'),
                Tables\Columns\TextColumn::make('order')->sortable(),
                Tables\Columns\BooleanColumn::make('is_active'),
            ])
            ->defaultSort('order')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSlides::route('/'),
            'create' => Pages\CreateSlide::route('/create'),
            'edit' => Pages\EditSlide::route('/{record}/edit'),
        ];
    }

    public static function getGlobalActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
