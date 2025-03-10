<?php

namespace App\Models;

use App\Enums\Status;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Creator extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'description',
        'website_url',
        'online',
    ];

    protected $casts = [
        "id" => "integer",
        "date_of_birth" => "date",
        "online" => "boolean",
    ];

    protected $appends = ['name'];

    public function getNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class);
    }

    public static function getForm(): array
    {
        return [
            TextInput::make("first_name")->label("Prénom")->required(),
            TextInput::make("last_name")->label("Nom")->required(),
            DatePicker::make("date_of_birth")
                ->label("Date de naissance")
                ->required(),
            RichEditor::make("description")->label("Biographie")->required(),
            SpatieMediaLibraryFileUpload::make("avatar")
                ->collection("creators/profile")
                ->image()
                ->imageEditor()
                ->maxSize(40960)
                ->label("Photo")
                ->conversion('avatar-thumbnail')
                ->downloadable(),
            SpatieMediaLibraryFileUpload::make("avatar_hover")
                ->collection("creators/profile")
                ->image()
                ->imageEditor()
                ->maxSize(40960)
                ->label("Photo (hover)")
                ->conversion('avatar-thumbnail')
                ->downloadable(),
            TextInput::make("website_url")->label("Site web")->url(),
            Radio::make("online")
                ->options(Status::class)
                ->inline()
                ->default(Status::OFFLINE),
            Select::make('collections')
                ->relationship('collections', 'name')
                ->multiple()
                ->preload()
                ->searchable()
                ->label('Collections'),
        ];
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        // Avatar conversions
        $this->addMediaConversion('avatar-thumbnail')
            ->width(100)
            ->height(100)
            ->format('webp')
            ->quality(90)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('creators/avatars');

        $this->addMediaConversion('avatar-small')
            ->width(200)
            ->height(200)
            ->format('webp')
            ->quality(90)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('creators/avatars');

        $this->addMediaConversion('avatar-medium')
            ->width(400)
            ->height(400)
            ->format('webp')
            ->quality(85)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('creators/avatars');

        // Profile/Banner images
        $this->addMediaConversion('profile-small')
            ->width(800)
            ->height(600)
            ->format('webp')
            ->quality(90)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('creators/profile');

        $this->addMediaConversion('profile-medium')
            ->width(1200)
            ->height(900)
            ->format('webp')
            ->quality(85)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('creators/profile');

        $this->addMediaConversion('profile-large')
            ->width(1600)
            ->height(1200)
            ->format('webp')
            ->quality(80)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('creators/profile');
    }
}
