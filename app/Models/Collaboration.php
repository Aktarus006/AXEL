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

class Collaboration extends Model
{
    use HasFactory;

    protected $casts = [
        "id" => "integer",
        "online" => "boolean",
        "creation_date" => "date",
        "collection_id" => "integer",
    ];

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public function creators(): BelongsToMany
    {
        return $this->belongsToMany(Creator::class);
    }

    public static function getForm(): array
    {
        return [
            TextInput::make("name")->label("Nom")->required(),
            RichEditor::make("description")->label("Description")->required(),
            SpatieMediaLibraryFileUpload::make("image")
                ->collection("collaborations")
                ->label("Image")
                ->rules("image")
                ->required(),
            Select::make("collection_id")
                ->label("Collection")
                ->preload()
                ->createOptionForm(Collection::getForm())
                ->editOptionForm(Collection::getForm())
                ->searchable()
                ->relationship("collection", "name"),
            Select::make("creators")
                ->label("Créateurs")
                ->preload()
                ->createOptionForm(Creator::getForm())
                ->editOptionForm(Creator::getForm())
                ->searchable()
                ->relationship("creators", "last_name"),
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
