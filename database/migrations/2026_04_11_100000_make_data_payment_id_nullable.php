<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bulk rows are created before a Payment exists; payment_id is set in DataController::storeFromBulkOrder after checkout.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if (! in_array($driver, ['mysql', 'mariadb'], true)) {
            return;
        }

        foreach (['data', 'datas'] as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'payment_id')) {
                continue;
            }

            DB::statement("ALTER TABLE `{$table}` MODIFY `payment_id` BIGINT UNSIGNED NULL");
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if (! in_array($driver, ['mysql', 'mariadb'], true)) {
            return;
        }

        foreach (['data', 'datas'] as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'payment_id')) {
                continue;
            }

            DB::statement("ALTER TABLE `{$table}` MODIFY `payment_id` BIGINT UNSIGNED NOT NULL");
        }
    }
};
