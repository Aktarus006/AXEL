<?php

namespace App\Filament\Resources\JewelResource\Pages;

use App\Filament\Resources\JewelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;

class EditJewel extends EditRecord
{
    use WithFileUploads;

    protected static string $resource = JewelResource::class;

    public $files = [
        "packshots" => [],
        "lifestyle" => [],
    ];

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    /**
     * Handle file updates using the generic updated hook.
     * This is more reliable for nested properties like files.lifestyle.
     */
    public function updated($propertyName)
    {
        if (!str_starts_with($propertyName, 'files.')) {
            return;
        }

        $key = str_replace('files.', '', $propertyName);
        $value = $this->files[$key];

        if (empty($value)) {
            return;
        }

        $collectionMap = [
            "packshots" => "jewels/packshots",
            "lifestyle" => "jewels/lifestyle",
        ];

        $collectionName = $collectionMap[$key] ?? null;

        if (!$collectionName) {
            Log::error("Unknown collection key for upload: " . $key);
            return;
        }

        try {
            // Validation with custom message
            $this->validate([
                $propertyName . ".*" => "image|mimes:jpeg,png,webp,svg+xml|max:20480",
            ], [
                $propertyName . ".*.max" => "L'image est trop volumineuse (maximum 20 Mo).",
            ]);

            // Process uploads
            $files = is_array($value) ? $value : [$value];
            
            foreach ($files as $file) {
                $this->record
                    ->addMedia($file->getRealPath())
                    ->usingName($file->getClientOriginalName())
                    ->usingFileName($file->hashName())
                    ->toMediaCollection($collectionName);
            }

            // Clear the files property
            $this->files[$key] = [];

            Notification::make()
                ->success()
                ->title("Images ajoutées")
                ->send();

            $this->dispatch("media-uploaded");
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Validation failed for files upload: " . $key, [
                'errors' => $e->errors(),
                'key' => $key
            ]);
            
            Notification::make()
                ->danger()
                ->title("Erreur de validation")
                ->body(collect($e->errors())->flatten()->first())
                ->send();
                
            throw $e;
        } catch (\Exception $e) {
            Log::error("Error uploading files: " . $e->getMessage());
            
            Notification::make()
                ->danger()
                ->title("Erreur lors de l'envoi")
                ->send();
        }
    }

    #[On("media-uploaded")]
    #[On("media-moved")]
    #[On("media-deleted")]
    public function refresh()
    {
        $this->record->refresh();
    }

    public function moveMedia($mediaId, $toCollection)
    {
        try {
            Log::info("Moving media {$mediaId} to {$toCollection}");
            $allowedCollections = ["jewels/packshots", "jewels/lifestyle"];
            if (!in_array($toCollection, $allowedCollections)) {
                throw new \Exception("Collection non autorisée: {$toCollection}");
            }
            
            $media = $this->record->media()->find($mediaId);
            if ($media) {
                $media->move($this->record, $toCollection);
                Notification::make()->success()->title("Image déplacée")->send();
                $this->dispatch("media-moved");
            } else {
                Log::warning("Media {$mediaId} not found for moving.");
                Notification::make()->danger()->title("Image introuvable")->send();
            }
        } catch (\Exception $e) {
            Log::error("Error moving media: " . $e->getMessage());
            Notification::make()->danger()->title("Erreur lors du déplacement")->body($e->getMessage())->send();
        }
    }

    public function deleteMedia($mediaId)
    {
        try {
            Log::info("Attempting to delete media {$mediaId}");
            $media = $this->record->media()->find($mediaId);
            if ($media) {
                Log::info("Media found, deleting: " . $media->file_name);
                $media->delete();
                Notification::make()->success()->title("Image supprimée")->send();
                $this->dispatch("media-deleted");
            } else {
                Log::warning("Media {$mediaId} not found for deletion.");
                Notification::make()->danger()->title("Image introuvable")->send();
            }
        } catch (\Exception $e) {
            Log::error("Error deleting media: " . $e->getMessage(), [
                'mediaId' => $mediaId,
                'exception' => $e
            ]);
            Notification::make()->danger()->title("Erreur lors de la suppression")->body($e->getMessage())->send();
        }
    }
}
