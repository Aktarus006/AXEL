<?php

namespace App\Filament\Resources\JewelResource\Pages;

use App\Filament\Resources\JewelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;

class EditJewel extends EditRecord
{
    use WithFileUploads;

    protected static string $resource = JewelResource::class;

    public $files = [
        "jewels/packshots" => [],
        "jewels/lifestyle" => [],
    ];

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    public function updatedFiles($value, $key)
    {
        // 1. VALIDATION CRUCIALE
        $this->validate([
            "files." .
            $key .
            ".*" => "image|mimes:jpeg,png,webp,svg+xml|max:10240",
        ]);

        // 2. Traitement seulement si la validation passe
        foreach ($value as $file) {
            $this->record
                ->addMedia($file->getRealPath())
                ->usingName($file->getClientOriginalName())
                ->usingFileName($file->hashName()) // Sécurité: on change le nom du fichier sur le disque
                ->toMediaCollection($key);
        }

        $this->files[$key] = [];

        Notification::make()
            ->success()
            ->title("Images ajoutées en toute sécurité")
            ->send();

        $this->dispatch("media-uploaded");
    }

    public function moveMedia($mediaId, $toCollection)
    {
        $allowedCollections = ["jewels/packshots", "jewels/lifestyle"];
        if (!in_array($toCollection, $allowedCollections)) {
            throw new \Exception("Collection non autorisée");
        }
        $media = $this->record->media()->find($mediaId);
        if ($media) {
            $media->move($this->record, $toCollection);
            Notification::make()->success()->title("Image déplacée")->send();

            $this->dispatch("media-moved");
        }
    }

    public function deleteMedia($mediaId)
    {
        $allowedCollections = ["jewels/packshots", "jewels/lifestyle"];
        if (!in_array($toCollection, $allowedCollections)) {
            throw new \Exception("Collection non autorisée");
        }
        $media = $this->record->media()->find($mediaId);
        if ($media) {
            $media->delete();
            Notification::make()->success()->title("Image supprimée")->send();

            $this->dispatch("media-deleted");
        }
    }
}
