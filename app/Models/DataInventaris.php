<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataInventaris extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'data_inventaris';

    // Jika Anda ingin mengizinkan mass assignment
    protected $fillable = [
        'inventaris_id',
        'barang',
        'type',
        'serial_number',
        'spesifikasi',
        'status',
        'user_id',
        'department'
    ];
}
