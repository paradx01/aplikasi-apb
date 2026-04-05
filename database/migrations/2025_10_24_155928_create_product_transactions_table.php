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
        Schema::table('product_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');    
            $table->unsignedBigInteger('total_amount');
            $table->boolean('is_paid')->default(false);
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('post_code')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('notes')->nullable();
            $table->string('proof')->nullable(); // varchar(255)
            $table->enum('status', [
                'pending', 
                'paid', 
                'process', 
                'shipped', 
                'delivered', 
                'success', 
                'canceled'
            ])->default('pending');
            // Optional: Simpan reference ke user_address (untuk tracking)
            $table->foreignId('user_address_id')->nullable()->constrained()->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_transactions', function (Blueprint $table) {
            $table->dropForeign(['user_address_id']);
            $table->dropColumn([
                'address', 
                'city', 
                'post_code', 
                'phone_number', 
                'notes',
                'user_address_id'
            ]);
        });
    }
};
