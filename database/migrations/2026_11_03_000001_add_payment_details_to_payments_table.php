<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('status');
            }
            if (!Schema::hasColumn('payments', 'lenco_reference')) {
                $table->string('lenco_reference')->nullable()->after('transaction_id');
            }
            if (!Schema::hasColumn('payments', 'operator')) {
                $table->string('operator')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('payments', 'card_last4')) {
                $table->string('card_last4', 4)->nullable()->after('operator');
            }
            if (!Schema::hasColumn('payments', 'fee')) {
                $table->decimal('fee', 10, 2)->nullable()->after('amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            foreach (['payment_method', 'lenco_reference', 'operator', 'card_last4', 'fee'] as $column) {
                if (Schema::hasColumn('payments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
