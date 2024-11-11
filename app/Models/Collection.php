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

class Collection extends Model
{
    use HasFactory;

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
                ->label("Image")
                ->rules("image")
                ->required(),
            FileUpload::make("cover")
                ->label("Cover")
                ->rules("image")
                ->directory("collections")
                ->imageEditor()
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
