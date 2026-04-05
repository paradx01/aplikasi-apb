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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->tinyInteger('age')->nullable();
            $table->enum('gender', ['L', 'P']);
            
            // Profil Kesehatan (Penyakit Kronis & Kondisi Medis)
            $table->boolean('has_hypertension')->default(false);
            $table->boolean('is_pregnant')->default(false);
            $table->boolean('has_kidney_disorder')->default(false);
            $table->boolean('has_heart_disorder')->default(false);
            $table->boolean('has_diabetes')->default(false);
            $table->boolean('has_stomach_ulcer')->default(false);
            $table->boolean('has_liver_disorder')->default(false);
            $table->boolean('has_asthma')->default(false);
            
            // Riwayat Alergi (Bagian 1)
            $table->boolean('has_allergy_nsaid')->default(false);
            $table->boolean('has_allergy_aspirin')->default(false);
            
            // Kondisi Medis Spesifik Lainnya
            $table->boolean('has_glaucoma')->default(false);
            $table->boolean('has_prostate_disorder')->default(false);
            $table->boolean('has_hyperthyroidism')->default(false);
            $table->boolean('has_g6pd_deficiency')->default(false);
            
            // Riwayat Alergi (Bagian 2)
            $table->boolean('has_allergy_paracetamol')->default(false);
            $table->boolean('has_allergy_antihistamine')->default(false);
            $table->boolean('has_allergy_decongestant')->default(false);
            $table->boolean('has_allergy_bromhexine')->default(false);
            $table->boolean('has_allergy_guaifenesin')->default(false);
            $table->boolean('has_allergy_antacid')->default(false);
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
