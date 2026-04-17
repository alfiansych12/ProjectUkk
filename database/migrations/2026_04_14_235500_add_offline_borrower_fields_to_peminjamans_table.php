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
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->string('borrower_name')->nullable()->after('user_id');
            $table->string('borrower_whatsapp')->nullable()->after('borrower_name');
            $table->timestamp('otp_verified_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropColumn(['borrower_name', 'borrower_whatsapp', 'otp_verified_at']);
        });
    }
};
