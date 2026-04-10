<?php

namespace App\Models;

use App\Enums\Status;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
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

    protected $fillable = [
        "title",
        "description",
        "online",
        "featured",
        "creation_date",
        "jewel_id",
        "collection_id",
        "external_url",
    ];

    protected $casts = [
        "id" => "integer",
        "online" => Status::class,
        "featured" => "boolean",
        "creation_date" => "date",
    ];

    public function creators(): BelongsToMany
    {
        return $this->belongsToMany(Creator::class);
    }

    public function jewel(): BelongsTo
    {
        return $this->belongsTo(Jewel::class);
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public static function getForm(): array
    {
        return [
            TextInput::make("title")->label("Titre")->required(),
            RichEditor::make("description")->label("Contenu")->required(),
            TextInput::make("external_url")
                ->label("Lien externe (presse, site tiers, etc.)")
                ->url()
                ->placeholder('https://www.site-externe.com/article')
                ->suffixIcon('heroicon-m-globe-alt'),
            SpatieMediaLibraryFileUpload::make("image")
                ->collection("news/images")
                ->label("Image")
                ->rules("image")
                ->required()
                ->imageEditor()
                ->maxSize(40960)
                ->acceptedFileTypes([
                    "image/jpeg",
                    "image/png",
                    "image/webp",
                    "image/svg+xml",
                ])
                ->conversion("thumbnail")
                ->downloadable(),
            Radio::make("online")
                ->options(Status::class)
                ->inline()
                ->default(Status::OFFLINE),
            Toggle::make("featured")
                ->label("Mettre en avant (Featured)")
                ->default(false)
                ->helperText("Les articles mis en avant apparaissent en haut de la liste."),
            DatePicker::make("creation_date")
                ->label("Date de création")
                ->required(),
            Select::make("jewel_id")
                ->label("Bijou lié")
                ->relationship("jewel", "name")
                ->searchable()
                ->preload(),
            Select::make("collection_id")
                ->label("Série liée")
                ->relationship("collection", "name")
                ->searchable()
                ->preload(),
            Select::make("creators")
                ->label("Créateurs liés")
                ->multiple()
                ->relationship("creators", "last_name")
                ->getOptionLabelFromRecordUsing(fn($record) => "{$record->first_name} {$record->last_name}")
                ->searchable()
                ->preload(),
        ];
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        // Thumbnail for news list
        $this->addMediaConversion("thumbnail")
            ->width(400)
            ->height(300)
            ->format("webp")
            ->quality(80)
            ->sharpen(10)
            ->nonQueued()
            ->performOnCollections("news/images");

        // Small size for cards
        $this->addMediaConversion("small")
            ->width(800)
            ->height(600)
            ->format("webp")
            ->quality(80)
            ->sharpen(10)
            ->nonQueued()
            ->performOnCollections("news/images");

        // Medium size for article preview
        $this->addMediaConversion("medium")
            ->width(1200)
            ->height(900)
            ->format("webp")
            ->quality(80)
            ->sharpen(10)
            ->nonQueued()
            ->performOnCollections("news/images");

        // Large size for article header
        $this->addMediaConversion("large")
            ->width(1600)
            ->height(1200)
            ->format("webp")
            ->quality(80)
            ->sharpen(10)
            ->nonQueued()
            ->performOnCollections("news/images");

        // Full width banner
        $this->addMediaConversion("banner")
            ->width(1920)
            ->height(1080)
            ->format("webp")
            ->quality(80)
            ->sharpen(10)
            ->nonQueued()
            ->performOnCollections("news/images");
    }
}
