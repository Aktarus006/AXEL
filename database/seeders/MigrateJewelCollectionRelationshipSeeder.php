<?php

namespace Database\Seeders;

use App\Models\Jewel;
use Illuminate\Database\Seeder;

class MigrateJewelCollectionRelationshipSeeder extends Seeder
{
    public function run(): void
    {
        Jewel::whereNotNull('collection_id')->each(function ($jewel) {
            $jewel->collections()->attach($jewel->collection_id);
        });
    }
}
