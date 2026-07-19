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
        // Drop triggers if they exist
        DB::unprepared('DROP TRIGGER IF EXISTS prevent_audit_logs_update;');
        DB::unprepared('DROP TRIGGER IF EXISTS prevent_audit_logs_delete;');

        // Create BEFORE UPDATE trigger
        DB::unprepared('
            CREATE TRIGGER prevent_audit_logs_update 
            BEFORE UPDATE ON audit_logs 
            FOR EACH ROW 
            BEGIN
                SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "Database Engine Constraint: audit_logs table is Append Only (Read/Insert Only). UPDATE is strictly prohibited.";
            END
        ');

        // Create BEFORE DELETE trigger
        DB::unprepared('
            CREATE TRIGGER prevent_audit_logs_delete 
            BEFORE DELETE ON audit_logs 
            FOR EACH ROW 
            BEGIN
                SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "Database Engine Constraint: audit_logs table is Append Only (Read/Insert Only). DELETE is strictly prohibited.";
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS prevent_audit_logs_update;');
        DB::unprepared('DROP TRIGGER IF EXISTS prevent_audit_logs_delete;');
    }
};
