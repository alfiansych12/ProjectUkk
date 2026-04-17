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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_kembalikan_alat");
        DB::unprepared("
            CREATE PROCEDURE sp_kembalikan_alat(
                IN p_peminjaman_id INT, 
                IN p_tgl_kembali_real DATE, 
                IN p_petugas_id INT
            )
            BEGIN
                DECLARE v_denda DECIMAL(10,2);
                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    ROLLBACK;
                    RESIGNAL;
                END;

                START TRANSACTION;
                    
                    SET v_denda = hitung_denda(p_peminjaman_id, p_tgl_kembali_real);

                    -- Log to separate table for history
                    INSERT INTO pengembalians (peminjaman_id, tgl_kembali_real, denda, petugas_id, created_at, updated_at)
                    VALUES (p_peminjaman_id, p_tgl_kembali_real, v_denda, p_petugas_id, NOW(), NOW());

                    -- Update core table for easy reporting
                    UPDATE peminjamans 
                    SET status = 'returned', 
                        denda = v_denda,
                        tgl_kembali_real = p_tgl_kembali_real 
                    WHERE id = p_peminjaman_id;

                COMMIT;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No change needed or revert to old if needed
    }
};
