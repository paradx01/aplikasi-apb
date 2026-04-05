<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recommendation_histories', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_transaction_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('disease_id')->constrained()->onDelete('cascade');
            $table->string('disease_name');
            $table->decimal('confidence', 5, 2)->nullable();
            // Gejala yang dipilih (JSON)
            $table->json('selected_symptoms');
            // Semua obat yang direkomendasikan (JSON)
            $table->json('recommended_products');
            // Obat yang dibeli (JSON array of product IDs)
            $table->json('purchased_product_ids')->nullable();
            $table->boolean('is_confirmed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendation_histories');
    }
};
