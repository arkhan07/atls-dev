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
        Schema::create('atls_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            
            // Personal Information
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->string('id_number')->nullable(); // KTP/Passport
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            
            // Professional Information
            $table->string('profession')->nullable(); // Dokter, Perawat, dll
            $table->string('institution')->nullable(); // Nama RS/Klinik/Institusi
            $table->string('specialization')->nullable();
            $table->string('license_number')->nullable(); // Nomor STR
            
            // Address
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            
            // Additional Information
            $table->text('notes')->nullable();
            $table->boolean('is_certified')->default(false); // Sudah pernah ikut ATLS sebelumnya?
            $table->date('previous_certification_date')->nullable();
            
            // Registration Status
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'pending', 'paid', 'refunded'])->default('unpaid');
            $table->decimal('amount_paid', 10, 2)->nullable();
            
            // Metadata
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('package_id');
            $table->index('status');
            $table->index('payment_status');
            $table->index('registered_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atls_registrations');
    }
};
