<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('lenco_reference');
                $table->index('payment_reference');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'payment_reference')) {
                $table->dropIndex(['payment_reference']);
                $table->dropColumn('payment_reference');
            }
        });
    }
};
