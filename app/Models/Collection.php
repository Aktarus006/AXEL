<?php

namespace App\Models;

use App\Enums\Status;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Collection extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $casts = [
        "id" => "integer",
        "online" => "boolean",
        "creation_date" => "date",
    ];

    public function jewels(): HasMany
    {
        return $this->hasMany(Jewel::class);
    }

    public static function getForm(): array
    {
        return [
            TextInput::make("name")->label("Nom")->required(),
            RichEditor::make("description")->label("Description")->required(),
            SpatieMediaLibraryFileUpload::make("image")
                ->collection("collections")
                ->maxsize(40960)
                ->optimize("webp")
                ->reorderable()
                ->multiple()
                ->label("Images")
                ->image(),
            FileUpload::make("cover")
                ->label("Cover")
                ->rules("image")
                ->directory("collections")
                ->imageEditor(),
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
        $this->addMediaConversion('thumbnail')
            ->width(400)
            ->height(400)
            ->format('webp')
            ->quality(90)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('collections/images');

        $this->addMediaConversion('small')
            ->width(800)
            ->height(800)
            ->format('webp')
            ->quality(90)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('collections/images');

        $this->addMediaConversion('medium')
            ->width(1200)
            ->height(1200)
            ->format('webp')
            ->quality(85)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('collections/images');

        $this->addMediaConversion('large')
            ->width(1600)
            ->height(1600)
            ->format('webp')
            ->quality(85)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('collections/images');

        // Banner-specific conversions with different aspect ratios
        $this->addMediaConversion('banner-small')
            ->width(800)
            ->height(400)
            ->format('webp')
            ->quality(90)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('collections/banners');

        $this->addMediaConversion('banner-medium')
            ->width(1200)
            ->height(600)
            ->format('webp')
            ->quality(85)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('collections/banners');

        $this->addMediaConversion('banner-large')
            ->width(1920)
            ->height(960)
            ->format('webp')
            ->quality(80)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('collections/banners');
    }
}
