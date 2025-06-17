<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable;

    protected $table = 'pelanggans';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'no_hp',
        'alamat',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relasi ke PemesananServis (jika diperlukan)
     */
    public function pemesananServis()
    {
        return $this->hasMany(PemesananServis::class, 'pelanggan_id');
    }
}
