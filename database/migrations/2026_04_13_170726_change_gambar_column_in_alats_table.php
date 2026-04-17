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
        Schema::table('alats', function (Blueprint $table) {
            $table->longText('images')->nullable()->after('kategori_id');
            // We keep 'gambar' for a while or drop it if we move data, 
            // but since it's a new project we can just drop it.
            $table->dropColumn('gambar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alats', function (Blueprint $table) {
            $table->string('gambar')->nullable()->after('kategori_id');
            $table->dropColumn('images');
        });
    }
};
