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
        Schema::create('driver_company_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('drivers')->onDelete('cascade');
            $table->foreignId('old_company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('new_company_id')->constrained('companies')->onDelete('cascade');
            $table->string('surat_pengunduran_diri')->nullable(); // File path
            $table->string('screenshot_quiz')->nullable(); // File path
            $table->text('notes')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_company_transfers');
    }
};
