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
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Creator extends Model 
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'description',
        'avatar',
        'avatar_hovered',
        'website_url',
        'online',
        'collection_id'
    ];

    protected $casts = [
        "id" => "integer",
        "date_of_birth" => "date",
        "online" => "boolean",
        "collaboration_id" => "integer",
    ];

    public function collaborations(): HasMany
    {
        return $this->hasMany(Collection::class);
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
            FileUpload::make("avatar")->image()->avatar()->imageEditor(),
            FileUpload::make("avatar_hovered")->image()->avatar()->imageEditor(),
            TextInput::make("website_url")->label("Site web")->url(),
            Radio::make("online")
                ->options(Status::class)
                ->inline()
                ->default(Status::OFFLINE),
            Select::make("collection_id")
                ->label("Collection")
                ->options(Collection::all()->pluck("name", "id")->toArray())
        ];
    }
}
