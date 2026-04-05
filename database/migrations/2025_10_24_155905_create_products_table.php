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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('photo');
            $table->unsignedBigInteger('price');
            $table->integer('stock');
            $table->string('active_ingredients');
            $table->text('contraindications');
            $table->text('composition')->nullable();
            $table->text('indications')->nullable();
            $table->text('side_effects')->nullable();
            $table->string('dosage_form', 50)->nullable();
            $table->string('unit', 20)->nullable(); 
            $table->char('pregnancy_category', 1)->nullable(); // Tipe Char(1) untuk 'A', 'B', 'C', 'D', 'X'
            $table->boolean('is_active')->default(true); 
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
