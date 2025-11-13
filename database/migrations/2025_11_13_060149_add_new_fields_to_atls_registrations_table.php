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
        Schema::table('atls_registrations', function (Blueprint $table) {
            // Update personal info fields
            $table->string('title')->nullable()->after('user_id'); // Dr., Prof., etc
            $table->string('nickname')->nullable()->after('full_name');
            $table->string('place_of_birth')->nullable()->after('email');
            $table->integer('age')->nullable()->after('birth_date');
            $table->string('religion')->nullable()->after('gender');
            $table->string('nik')->nullable()->after('id_number'); // NIK for Plataran Sehat
            $table->string('plataran_sehat_name')->nullable()->after('nik');
            $table->string('shirt_size')->nullable()->after('plataran_sehat_name'); // XS, S, M, L, XL, XXL, XXXL
            
            // Document upload
            $table->string('certificate_file')->nullable()->after('license_number'); // Medical Degree/Specialist Certificate
            
            // Shipping address for ATLS book
            $table->text('shipping_address')->nullable()->after('address');
            $table->string('shipping_city')->nullable()->after('shipping_address');
            $table->string('shipping_province')->nullable()->after('shipping_city');
            $table->string('shipping_postal_code')->nullable()->after('shipping_province');
            
            // Agreement
            $table->boolean('agreed_to_terms')->default(false)->after('notes');
            
            // Remove professional fields that are not needed
            // We'll keep them for backward compatibility but make nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('atls_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'nickname',
                'place_of_birth',
                'age',
                'religion',
                'nik',
                'plataran_sehat_name',
                'shirt_size',
                'certificate_file',
                'shipping_address',
                'shipping_city',
                'shipping_province',
                'shipping_postal_code',
                'agreed_to_terms'
            ]);
        });
    }
};
