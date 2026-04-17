<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Format semua nomor telepon ke format internasional (62xxx)
        $users = DB::table('users')->whereNotNull('phone')->get();
        
        foreach ($users as $user) {
            $phone = $user->phone;
            
            // Skip jika sudah dalam format internasional (62...)
            if (str_starts_with($phone, '62')) {
                continue;
            }
            
            // Format ke internasional
            $formattedPhone = $this->formatWhatsApp($phone);
            
            DB::table('users')
                ->where('id', $user->id)
                ->update(['phone' => $formattedPhone]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak ada rollback, karena ini adalah data correction
    }

    private function formatWhatsApp($number)
    {
        $whatsapp = preg_replace('/\D+/', '', $number);
        if (str_starts_with($whatsapp, '0')) {
            $whatsapp = '62' . substr($whatsapp, 1);
        } elseif (str_starts_with($whatsapp, '620')) {
            $whatsapp = '62' . substr($whatsapp, 3);
        } elseif (!str_starts_with($whatsapp, '62')) {
            $whatsapp = '62' . $whatsapp;
        }
        return $whatsapp;
    }
};
