<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('client_name');
            $table->string('client_badge')->nullable();
            $table->string('category'); // bags, furniture, interior
            $table->text('description')->nullable();
            $table->string('cta_text')->default('Get a Quote');
            $table->string('layout_type')->default('layout-wide'); // layout-wide, layout-right
            $table->string('gallery_type')->default('g-2'); // g-1, g-2, g-2x2, g-3, g-main-plus
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_projects');
    }
};
