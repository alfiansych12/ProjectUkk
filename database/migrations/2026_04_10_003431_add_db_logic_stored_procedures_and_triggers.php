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
        // Drop existing to avoid errors on migration
        DB::unprepared("DROP FUNCTION IF EXISTS hitung_denda");
        DB::unprepared("DROP TRIGGER IF EXISTS tr_peminjaman_after_update");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_kembalikan_alat");

        // 1. Function to calculate fine
        DB::unprepared("
            CREATE FUNCTION IF NOT EXISTS hitung_denda(p_peminjaman_id INT, p_tgl_kembali_real DATE) 
            RETURNS DECIMAL(10,2)
            DETERMINISTIC
            BEGIN
                DECLARE v_tgl_kembali_rencana DATE;
                DECLARE v_harga_sewa DECIMAL(10,2);
                DECLARE v_denda DECIMAL(10,2) DEFAULT 0;
                DECLARE v_hari_terlambat INT;

                SELECT tgl_kembali_rencana INTO v_tgl_kembali_rencana FROM peminjamans WHERE id = p_peminjaman_id;
                SELECT harga_sewa_per_hari INTO v_harga_sewa FROM alats a 
                JOIN peminjamans p ON a.id = p.alat_id WHERE p.id = p_peminjaman_id;

                SET v_hari_terlambat = DATEDIFF(p_tgl_kembali_real, v_tgl_kembali_rencana);
                
                IF v_hari_terlambat > 0 THEN
                    SET v_denda = v_hari_terlambat * 10000; -- Flat fine 10k per day late
                END IF;

                RETURN v_denda;
            END
        ");

        // 2. Trigger to update stock on borrowed
        DB::unprepared("
            CREATE TRIGGER tr_peminjaman_after_update
            AFTER UPDATE ON peminjamans
            FOR EACH ROW
            BEGIN
                IF NEW.status = 'borrowed' AND OLD.status != 'borrowed' THEN
                    UPDATE alats SET stok = stok - NEW.jumlah WHERE id = NEW.alat_id;
                ELSEIF NEW.status = 'returned' AND OLD.status != 'returned' THEN
                    UPDATE alats SET stok = stok + NEW.jumlah WHERE id = NEW.alat_id;
                END IF;
            END
        ");

        // 3. Stored Procedure with COMMIT and ROLLBACK
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

                    INSERT INTO pengembalians (peminjaman_id, tgl_kembali_real, denda, petugas_id, created_at, updated_at)
                    VALUES (p_peminjaman_id, p_tgl_kembali_real, v_denda, p_petugas_id, NOW(), NOW());

                    UPDATE peminjamans SET status = 'returned' WHERE id = p_peminjaman_id;

                COMMIT;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP FUNCTION IF EXISTS hitung_denda");
        DB::unprepared("DROP TRIGGER IF EXISTS tr_peminjaman_after_update");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_kembalikan_alat");
    }
};
