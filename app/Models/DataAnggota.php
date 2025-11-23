<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataAnggota extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'data_anggota';
    protected $fillable = [
        'nama', 
        'jabatan', 
        'departement',
    ];

    // Jika Anda ingin mengizinkan mass assignment
    public function inventaris(): HasMany
    {
        return $this->hasMany(DataInventaris::class, 'assign');
    }
}
