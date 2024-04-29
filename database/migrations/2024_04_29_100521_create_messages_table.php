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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained()->onDelete('cascade');
            $table->foreignId('recipient_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->foreignId('encrypted_key_id')->constrained('user_keys')->onDelete('cascade');
            $table->enum('expiry_type', ['1', '2'])->default('1')->comment('1 for read_once and 2 for after_x_min');;
            $table->integer('expiry_time_minutes')->default(10);
            $table->softDeletes(); // Add soft delete column
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
