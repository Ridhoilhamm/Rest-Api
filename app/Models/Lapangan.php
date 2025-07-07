<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Lapangan extends Model
{
     use HasFactory;

    // UUID sebagai primary key
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'description',
        'thumbnail',
        'detail_photos',
        'harga_per_jam'
    ];

    // Cast detail_photos ke array supaya otomatis decode dari JSON
    protected $casts = [
        'detail_photos' => 'array',
    ];

    // Buat UUID otomatis saat create
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    /**
     * Relasi ke Booking (satu lapangan bisa banyak booking)
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
