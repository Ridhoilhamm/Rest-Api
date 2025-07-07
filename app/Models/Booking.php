<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Lapangan;

class Booking extends Model
{
    use HasFactory;

    // UUID sebagai primary key
    protected $keyType = 'string';
    public $incrementing = false;

    // Kolom yang boleh diisi mass-assignment
    protected $fillable = [
        'id',
        'user_id',
        'lapangan_id',
        'booking_date',
        'start_time',
        'end_time',
        'status',
        'jumlah_bayar',
    ];

    /**
     * Boot method: generate UUID otomatis saat membuat booking baru
     */
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
     * Relasi: Booking milik User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Booking milik Lapangan
     */
    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }
}
