<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataInventaris extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'data_inventaris';

    protected $fillable = ['inventaris_id', 'barang', 'type', 'serial_number', 'spesifikasi', 'status', 'assign', 'department'];
    public function anggota(): BelongsTo
    {
        return $this->belongsTo(DataAnggota::class, 'assign', 'id');
    }
}
