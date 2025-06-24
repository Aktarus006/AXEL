<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('slides', function (Blueprint $table) {
            $table->string('cta_text')->nullable()->after('background_color');
            $table->string('cta_background_color')->nullable()->after('cta_text');
        });
    }

    public function down(): void
    {
        Schema::table('slides', function (Blueprint $table) {
            $table->dropColumn(['cta_text', 'cta_background_color']);
        });
    }
};
