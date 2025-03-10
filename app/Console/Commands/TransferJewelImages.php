<?php

namespace App\Console\Commands;

use App\Models\Jewel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TransferJewelImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jewels:transfer-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer existing jewel images to the packshots collection';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting image transfer...');

        // Vérifier si le dossier storage existe
        if (!Storage::disk('public')->exists('/')) {
            $this->error('Storage directory does not exist!');
            $this->info('Current storage path: ' . storage_path('app/public'));
            return Command::FAILURE;
        }

        $jewels = Jewel::with('media')->get();
        $this->info("Found {$jewels->count()} jewels to process");
        $bar = $this->output->createProgressBar(count($jewels));

        $transferredFiles = 0;
        $errors = [];

        foreach ($jewels as $jewel) {
            $oldMedia = $jewel->getMedia('jewels/images');
            $this->info("\nProcessing jewel: {$jewel->name} (ID: {$jewel->id})");
            $this->info("Found {$oldMedia->count()} media items");

            foreach ($oldMedia as $media) {
                try {
                    $path = $media->getPath();
                    $this->info("Checking file: {$path}");

                    // Vérifier si le fichier existe
                    if (!Storage::disk('public')->exists($path)) {
                        $this->warn("File not found at: {$path}");
                        $errors[] = "File not found: {$path} for jewel {$jewel->name}";
                        continue;
                    }

                    // Copier vers la collection packshots
                    $newMedia = $jewel->copyMedia($path)
                        ->toMediaCollection('jewels/packshots', 'public');

                    $this->info("Successfully copied {$media->file_name} to packshots");
                    $transferredFiles++;
                } catch (\Exception $e) {
                    $this->error("Error: " . $e->getMessage());
                    $errors[] = "Error processing {$media->file_name} for jewel {$jewel->name}: " . $e->getMessage();
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Transferred {$transferredFiles} files");

        if (count($errors) > 0) {
            $this->error("Encountered errors:");
            foreach ($errors as $error) {
                $this->error("- " . $error);
            }
        }

        $this->info('Regenerating image conversions...');
        $this->call('media-library:regenerate');

        return Command::SUCCESS;
    }
}
