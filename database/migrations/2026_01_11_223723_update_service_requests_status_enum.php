<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("
                ALTER TABLE service_requests
                DROP CONSTRAINT IF EXISTS service_requests_status_check
            ");

            DB::statement("
                ALTER TABLE service_requests
                ADD CONSTRAINT service_requests_status_check
                CHECK (status IN (
                    'pending',
                    'accepted',
                    'provider_done',
                    'completed',
                    'cancelled'
                ))
            ");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("
                ALTER TABLE service_requests
                DROP CONSTRAINT IF EXISTS service_requests_status_check
            ");

            DB::statement("
                ALTER TABLE service_requests
                ADD CONSTRAINT service_requests_status_check
                CHECK (status IN (
                    'pending',
                    'accepted',
                    'completed',
                    'cancelled'
                ))
            ");
        }
    }
};
