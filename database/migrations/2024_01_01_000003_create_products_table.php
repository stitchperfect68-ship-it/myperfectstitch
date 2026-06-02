<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->foreignId('subcategory_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->decimal('price', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->string('short_description')->nullable();
            $table->string('tag_type')->nullable();
            $table->string('tag_label')->nullable();
            $table->integer('stock_qty')->default(999);
            $table->boolean('track_stock')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
