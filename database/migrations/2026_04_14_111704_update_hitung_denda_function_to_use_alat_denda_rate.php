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
        DB::unprepared("DROP FUNCTION IF EXISTS hitung_denda");
        DB::unprepared("
            CREATE FUNCTION hitung_denda(p_peminjaman_id INT, p_tgl_kembali_real DATE) 
            RETURNS DECIMAL(10,2)
            DETERMINISTIC
            BEGIN
                DECLARE v_tgl_kembali_rencana DATE;
                DECLARE v_denda_per_hari DECIMAL(10,2);
                DECLARE v_denda DECIMAL(10,2) DEFAULT 0;
                DECLARE v_hari_terlambat INT;
                DECLARE v_jumlah INT;

                SELECT tgl_kembali_rencana, jumlah INTO v_tgl_kembali_rencana, v_jumlah 
                FROM peminjamans WHERE id = p_peminjaman_id;
                
                SELECT denda_per_hari INTO v_denda_per_hari 
                FROM alats a 
                JOIN peminjamans p ON a.id = p.alat_id 
                WHERE p.id = p_peminjaman_id;

                SET v_hari_terlambat = DATEDIFF(p_tgl_kembali_real, v_tgl_kembali_rencana);
                
                IF v_hari_terlambat > 0 THEN
                    -- Denda dihitung per hari terlambat per unit alat (biar fair)
                    SET v_denda = v_hari_terlambat * v_denda_per_hari * v_jumlah;
                END IF;

                RETURN v_denda;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to revert specifically here unless wanted
    }
};
