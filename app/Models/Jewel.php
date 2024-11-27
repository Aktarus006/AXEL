<?php

namespace App\Models;

use App\Enums\Material;
use App\Enums\Status;
use App\Enums\Type;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Jewel extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $casts = [
        "id" => "integer",
        "price" => "float",
        "online" => Status::class,
        "material" => AsEnumCollection::class . ":" . Material::class,
        "type" => AsEnumCollection::class . ":" . Type::class,
        "creation_date" => "date",
        "collection_id" => "integer",
        "collaboration_id" => "integer",
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('jewels/images');
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
            ->performOnCollections('jewels/images');

        $this->addMediaConversion('small')
            ->width(800)
            ->height(800)
            ->format('webp')
            ->quality(90)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('jewels/images');

        $this->addMediaConversion('medium')
            ->width(1200)
            ->height(1200)
            ->format('webp')
            ->quality(85)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('jewels/images');

        $this->addMediaConversion('large')
            ->width(1600)
            ->height(1600)
            ->format('webp')
            ->quality(85)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('jewels/images');

        $this->addMediaConversion('hd')
            ->width(2400)
            ->height(2400)
            ->format('webp')
            ->quality(80)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('jewels/images');
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public static function getForm(): array
    {
        return [
            Section::make("Informations")
                ->columns(2)
                ->schema([
                    TextInput::make("name")->label("Nom")->required(),
                    TextInput::make("price")
                        ->label("Prix")
                        ->numeric()
                        ->prefix("€"),
                    RichEditor::make("description")
                        ->label("Description")
                        ->columnSpan(2),
                    Select::make("collection_id")
                        ->label("Collection")
                        ->preload()
                        ->createOptionForm(Collection::getForm())
                        ->editOptionForm(Collection::getForm())
                        ->searchable()
                        ->relationship("collection", "name"),
                    Select::make("material")
                        ->label("Matière")
                        ->searchable()
                        ->multiple()
                        ->options(Material::class),
                    Select::make("type")
                        ->label("Type")
                        ->multiple()
                        ->options(Type::class),
                    Radio::make("online")
                        ->options(Status::class)
                        ->inline()
                        ->default(Status::OFFLINE),
                ]),
            Section::make("Images")->schema([
                FileUpload::make("cover")
                    ->label("Image de couverture")
                    ->directory("jewels/cover")
                    ->maxSize(40960)
                    ->optimize("webp")
                    ->imageEditor(),
                SpatieMediaLibraryFileUpload::make("image")
                    ->label("Images")
                    ->collection("jewels/images")
                    ->maxSize(40960)
                    ->optimize("webp")
                    ->reorderable()
                    ->multiple()
                    ->image(),
            ]),
        ];
    }
}
