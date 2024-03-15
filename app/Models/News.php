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
}
