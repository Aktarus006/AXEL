<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class Slide extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        "title",
        "button_text",
        "button_url",
        "order",
        "is_active",
        "background_color",
        "cta_background_color",
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion("desktop")
            ->width(1920)
            ->format("webp")
            ->optimize()
            ->performOnCollections("slides");

        $this->addMediaConversion("mobile")
            ->width(800)
            ->format("webp")
            ->optimize()
            ->performOnCollections("slides");

        $this->addMediaConversion("thumb")
            ->width(400)
            ->format("webp")
            ->optimize()
            ->performOnCollections("slides");
    }
    public static function getForm(): array
    {
        return [
            Section::make("Informations")
                ->columns(2)
                ->schema([
                    \Filament\Forms\Components\RichEditor::make("title")
                        ->label("Titre")
                        ->required()
                        ->toolbarButtons(["bold"])
                        ->extraAttributes([
                            "style" =>
                                "min-height: 80px; height: 80px; max-height: 120px;",
                        ]),
                    TextInput::make("button_text")->label("Texte du bouton"),
                    TextInput::make("button_url")->label("Lien du bouton"),
                    TextInput::make("background_color")->label(
                        "Couleur de fond",
                    ),
                    TextInput::make("cta_background_color")->label(
                        "Couleur de fond du CTA",
                    ),
                    TextInput::make("order")
                        ->label("Ordre")
                        ->numeric()
                        ->default(0),
                    Toggle::make("is_active")->label("Active")->default(true),
                ]),
            Section::make("Image")
                ->description(
                    "Image principale de la slide, optimisée pour le web",
                )
                ->schema([
                    SpatieMediaLibraryFileUpload::make("images")
                        ->collection("slides")
                        ->image()
                        ->multiple(false)
                        ->required()
                        ->maxSize(30720)
                        ->acceptedFileTypes([
                            "image/jpeg",
                            "image/png",
                            "image/webp",
                            "image/svg+xml",
                        ])
                        ->columnSpan(2),
                ]),
        ];
    }
}
