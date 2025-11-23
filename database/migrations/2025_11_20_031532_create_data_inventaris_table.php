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
        Schema::create('data_inventaris', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('inventaris_id')->unique();
            $table->string('barang');
            $table->string('type');
            $table->string('serial_number')->nullable();
            $table->string('spesifikasi');
            $table->enum('status', ['Baik', 'Rusak', 'Tidak Dipakai', 'Dilelang']);

            $table->foreignId('assign')
              ->nullable()
              ->constrained('data_anggota') // Merujuk ke tabel data_anggota
              ->onDelete('set null');

            $table->string('department');

            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_inventaris');
    }
};
