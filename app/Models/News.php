<?php

namespace App\Models;

use App\Enums\Status;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class News extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $casts = [
        "id" => "integer",
        "online" => "boolean",
        "creation_date" => "date",
    ];

    public function collaboration(): BelongsTo
    {
        return $this->belongsTo(Collaboration::class);
    }

    public function creators(): BelongsToMany
    {
        return $this->belongsToMany(Creator::class);
    }

    public static function getForm(): array
    {
        return [
            TextInput::make("title")->label("Titre")->required(),
            RichEditor::make("description")->label("Contenu")->required(),
            SpatieMediaLibraryFileUpload::make("image")
                ->collection("news")
                ->label("Image")
                ->rules("image")
                ->required(),
            Radio::make("online")
                ->options(Status::class)
                ->inline()
                ->default(Status::OFFLINE),
            DatePicker::make("creation_date")
                ->label("Date de création")
                ->required(),
        ];
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        // Thumbnail for news list
        $this->addMediaConversion('thumbnail')
            ->width(400)
            ->height(300)
            ->format('webp')
            ->quality(90)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('news/images');

        // Small size for cards
        $this->addMediaConversion('small')
            ->width(800)
            ->height(600)
            ->format('webp')
            ->quality(90)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('news/images');

        // Medium size for article preview
        $this->addMediaConversion('medium')
            ->width(1200)
            ->height(900)
            ->format('webp')
            ->quality(85)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('news/images');

        // Large size for article header
        $this->addMediaConversion('large')
            ->width(1600)
            ->height(1200)
            ->format('webp')
            ->quality(80)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('news/images');

        // Full width banner
        $this->addMediaConversion('banner')
            ->width(1920)
            ->height(1080)
            ->format('webp')
            ->quality(80)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('news/banners');
    }
}
