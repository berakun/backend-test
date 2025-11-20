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

            // Kolom dari gambar (disesuaikan)
            $table->string('inventaris_id')->unique();
            $table->string('barang');
            $table->string('type');
            $table->string('serial_number')->nullable();
            $table->string('spesifikasi');
            $table->enum('status', ['Baik', 'Rusak', 'Tidak Dipakai', 'Dilelang']);

            // Kolom untuk menghubungkan ke tabel 'users' (Assign) dan 'departments' (jika ada)
            $table->foreignId('user_id')->nullable()->constrained('users'); // userA, userB, dll.
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
