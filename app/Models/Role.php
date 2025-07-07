<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // Jika nama tabel bukan "roles", definisikan seperti ini:
    // protected $table = 'roles';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'name',
        'label',
    ];

    /**
     * Relasi one-to-many ke User
     * Satu Role bisa dimiliki banyak User
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
