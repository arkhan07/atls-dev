<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('date_range');
            $table->string('days');
            $table->string('time');
            $table->string('location');
            $table->text('maps_link')->nullable();
            $table->integer('quota');
            $table->decimal('price', 15, 2);
            $table->string('bank_account');
            $table->string('bank_name');
            $table->string('account_name');
            $table->string('contact_name');
            $table->string('contact_phone');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('packages');
    }
};