<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jewels', function (Blueprint $table) {
            // Récupérer le nom exact de la clé étrangère
            $foreignKeys = Schema::getConnection()
                ->getDoctrineSchemaManager()
                ->listTableForeignKeys('jewels');

            foreach ($foreignKeys as $foreignKey) {
                if (in_array('collection_id', $foreignKey->getLocalColumns())) {
                    $table->dropForeign($foreignKey->getName());
                    break;
                }
            }

            $table->dropColumn('collection_id');
        });
    }

    public function down(): void
    {
        Schema::table('jewels', function (Blueprint $table) {
            $table->foreignId('collection_id')->nullable()->constrained();
        });
    }
};
