<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bulk_orders') && ! Schema::hasColumn('bulk_orders', 'service')) {
            Schema::table('bulk_orders', function (Blueprint $table) {
                $table->string('service', 16)->default('data')->after('uuid');
            });
        }

        if (! Schema::hasTable('airtimes')) {
            return;
        }

        if (! Schema::hasColumn('airtimes', 'payment_id')) {
            Schema::table('airtimes', function (Blueprint $table) {
                $table->unsignedBigInteger('payment_id')->nullable()->index()->after('id');
            });
        }

        if (! Schema::hasColumn('airtimes', 'bulk_order_id')) {
            Schema::table('airtimes', function (Blueprint $table) {
                $table->foreignId('bulk_order_id')->nullable()->after('payment_id')->constrained('bulk_orders')->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('airtimes', 'fulfillment_status')) {
            Schema::table('airtimes', function (Blueprint $table) {
                $table->string('fulfillment_status', 32)->default('pending')->after('status');
            });
        }

        if (! Schema::hasColumn('airtimes', 'provider_message')) {
            Schema::table('airtimes', function (Blueprint $table) {
                $table->text('provider_message')->nullable()->after('fulfillment_status');
            });
        }

        if (! Schema::hasColumn('airtimes', 'attempts')) {
            Schema::table('airtimes', function (Blueprint $table) {
                $table->unsignedTinyInteger('attempts')->default(0)->after('provider_message');
            });
        }

        if (! Schema::hasColumn('airtimes', 'last_attempt_at')) {
            Schema::table('airtimes', function (Blueprint $table) {
                $table->timestamp('last_attempt_at')->nullable()->after('attempts');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('airtimes')) {
            if (Schema::hasColumn('airtimes', 'bulk_order_id')) {
                Schema::table('airtimes', function (Blueprint $table) {
                    $table->dropConstrainedForeignId('bulk_order_id');
                });
            }
            foreach (['last_attempt_at', 'attempts', 'provider_message', 'fulfillment_status'] as $col) {
                if (Schema::hasColumn('airtimes', $col)) {
                    Schema::table('airtimes', function (Blueprint $table) use ($col) {
                        $table->dropColumn($col);
                    });
                }
            }
            if (Schema::hasColumn('airtimes', 'payment_id')) {
                Schema::table('airtimes', function (Blueprint $table) {
                    $table->dropColumn('payment_id');
                });
            }
        }

        if (Schema::hasTable('bulk_orders') && Schema::hasColumn('bulk_orders', 'service')) {
            Schema::table('bulk_orders', function (Blueprint $table) {
                $table->dropColumn('service');
            });
        }
    }
};
