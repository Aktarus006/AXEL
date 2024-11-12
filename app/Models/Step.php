<?php

namespace App\Models;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Step extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $casts = [
        "id" => "integer",
    ];

    public static function getForm(): array
    {
        return [
            Section::make("Etape")
                ->columns(2)
                ->schema([
                    TextInput::make("position")
                        ->label("Numéro de l'étape")
                        ->numeric()
                        ->required(),
                    TextInput::make("title")->label("Titre")->required(),
                    RichEditor::make("description")
                        ->label("Description")
                        ->columnSpan(2),
                    SpatieMediaLibraryFileUpload::make("image")
                        ->label("Images")
                        ->collection("steps/images")
                        ->maxSize(40960)
                        ->optimize("webp")
                        ->reorderable()
                        ->multiple()
                        ->image(),
                ]),
        ];
    }
}
