<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function dataTable(): string
    {
        if (Schema::hasTable('data')) {
            return 'data';
        }

        return 'datas';
    }

    public function up(): void
    {
        $t = $this->dataTable();

        if (! Schema::hasTable($t)) {
            return;
        }

        Schema::table($t, function (Blueprint $table) use ($t) {
            if (! Schema::hasColumn($t, 'bulk_order_id')) {
                $table->foreignId('bulk_order_id')->nullable()->after('id')->constrained('bulk_orders')->nullOnDelete();
            }
            if (! Schema::hasColumn($t, 'fulfillment_status')) {
                $table->string('fulfillment_status', 32)->default('pending')->after('status');
            }
            if (! Schema::hasColumn($t, 'provider_message')) {
                $table->text('provider_message')->nullable()->after('fulfillment_status');
            }
            if (! Schema::hasColumn($t, 'attempts')) {
                $table->unsignedTinyInteger('attempts')->default(0)->after('provider_message');
            }
            if (! Schema::hasColumn($t, 'last_attempt_at')) {
                $table->timestamp('last_attempt_at')->nullable()->after('attempts');
            }
            if (! Schema::hasColumn($t, 'payment_id')) {
                $table->unsignedBigInteger('payment_id')->nullable()->index()->after('uploaded_by');
            }
            if (! Schema::hasColumn($t, 'network_code')) {
                $table->string('network_code')->nullable()->after('phone_number');
            }
        });
    }

    public function down(): void
    {
        $t = $this->dataTable();

        if (! Schema::hasTable($t)) {
            return;
        }

        Schema::table($t, function (Blueprint $table) use ($t) {
            if (Schema::hasColumn($t, 'bulk_order_id')) {
                $table->dropConstrainedForeignId('bulk_order_id');
            }
            if (Schema::hasColumn($t, 'fulfillment_status')) {
                $table->dropColumn('fulfillment_status');
            }
            if (Schema::hasColumn($t, 'provider_message')) {
                $table->dropColumn('provider_message');
            }
            if (Schema::hasColumn($t, 'attempts')) {
                $table->dropColumn('attempts');
            }
            if (Schema::hasColumn($t, 'last_attempt_at')) {
                $table->dropColumn('last_attempt_at');
            }
        });
    }
};
