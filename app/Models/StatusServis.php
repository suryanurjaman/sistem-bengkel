<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusServis extends Model
{
    use HasFactory;

    protected $table = 'status_servis';

    protected $fillable = [
        'nama_status',
        'keterangan',
    ];

    // Relasi ke pemesanan_servis
    public function pemesananServis()
    {
        return $this->hasMany(PemesananServis::class, 'status_id');
    }


    public function statusServis()
    {
        return $this->belongsTo(StatusServis::class, 'status_id');
    }
}
