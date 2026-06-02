<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('ref')->unique();
            $table->string('service_type');
            $table->integer('quantity')->nullable();
            $table->string('budget')->nullable();
            $table->text('description')->nullable();
            $table->string('deadline')->nullable();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->enum('status', ['new', 'reviewed', 'quoted', 'converted', 'cancelled'])->default('new');
            $table->decimal('quoted_amount', 10, 2)->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('payment_link')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
