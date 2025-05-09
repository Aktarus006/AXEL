<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('creators', 'collection_id')) {
            Schema::table('creators', function (Blueprint $table) {
                $table->dropColumn('collection_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('creators', function (Blueprint $table) {
            $table->foreignId('collection_id')->nullable()->constrained()->onDelete('set null');
        });
    }
};
