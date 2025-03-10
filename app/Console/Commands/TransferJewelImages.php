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

        $jewels = Jewel::with('media')->get();
        $bar = $this->output->createProgressBar(count($jewels));

        $transferredFiles = 0;
        $errors = [];

        foreach ($jewels as $jewel) {
            $oldMedia = $jewel->getMedia('jewels/images');

            foreach ($oldMedia as $media) {
                try {
                    // Vérifier si le fichier existe
                    if (!Storage::disk('public')->exists($media->getPath())) {
                        $errors[] = "File not found: {$media->getPath()} for jewel {$jewel->name}";
                        continue;
                    }

                    // Copier vers la collection packshots
                    $newMedia = $jewel->copyMedia($media->getPath())
                        ->toMediaCollection('jewels/packshots', 'public');

                    $this->info("Copied {$media->file_name} to packshots for jewel {$jewel->name}");
                    $transferredFiles++;
                } catch (\Exception $e) {
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
