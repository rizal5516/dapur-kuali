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
        Schema::create('wedding_reservations', function (Blueprint $table) {
            $table->id();

            $table->date('event_date');
            $table->enum('time_slot', ['siang', 'malam', 'custom'])->default('custom');

            $table->unsignedInteger('guest_estimate')->default(0);

            $table->string('contact_name', 120);
            $table->string('phone', 30);
            $table->string('email', 190)->nullable();

            $table->text('notes')->nullable();

            $table->enum('status', ['pending', 'confirmed', 'rejected', 'cancelled'])
                ->default('pending');

            $table->foreignId('managed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'event_date']);
            $table->index(['event_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wedding_reservations');
    }
};
