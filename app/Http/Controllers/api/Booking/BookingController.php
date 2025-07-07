<?php

namespace App\Http\Controllers\api\Booking;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        // Ambil user dari Sanctum
        $user = auth('sanctum')->user();

        // Cek apakah user sudah login
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum login.',
            ], 401);
        }

        // Validasi request
        try {
            $validated = $request->validate([
                'lapangan_id'   => 'required|exists:lapangans,id',
                'booking_date'  => 'required|date',
                'start_time'    => 'required|date_format:H:i',
                'end_time'      => 'required|date_format:H:i|after:start_time',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $e->errors(),
            ], 422);
        }

        // Cek apakah ada booking yang bentrok
        $conflict = Booking::where('lapangan_id', $validated['lapangan_id'])
            ->where('booking_date', $validated['booking_date'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                            ->where('end_time', '>=', $validated['end_time']);
                    });
            })
            ->exists();

        if ($conflict) {
            return response()->json([
                'success' => false,
                'message' => 'Waktu booking bertabrakan dengan booking lain.',
            ], 409);
        }

        // Hitung durasi dan jumlah bayar
        $start = Carbon::createFromFormat('H:i', $validated['start_time']);
        $end = Carbon::createFromFormat('H:i', $validated['end_time']);
        $durasiJam = $end->diffInHours($start);

        $lapangan = Lapangan::findOrFail($validated['lapangan_id']);
        $jumlahBayar = $lapangan->harga_per_jam * $durasiJam;

        // Simpan booking baru
        $booking = Booking::create([
            'user_id'      => $user->id,
            'lapangan_id'  => $validated['lapangan_id'],
            'booking_date' => $validated['booking_date'],
            'start_time'   => $validated['start_time'],
            'end_time'     => $validated['end_time'],
            'status'       => 'pending',
            'jumlah_bayar' => $jumlahBayar,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil dibuat.',
            'data'    => $booking,
        ], 201);
    }
    // Function GetAllTransaksi
    public function index()
    {
        if (!auth('sanctum')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum login. Silakan login untuk mengakses data.',
            ], 401);
        }


        $bookings = Booking::with(['user', 'lapangan'])
            ->where('user_id', auth()->id())
            ->orderBy('booking_date', 'desc')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'user' => [
                        'id' => $booking->user->id,
                        'name' => $booking->user->name,
                        'email' => $booking->user->email,
                    ],
                    'lapangan' => [
                        'id' => $booking->lapangan->id,
                        'name' => $booking->lapangan->name,
                        'thumbnail' => $booking->lapangan->thumbnail,
                    ],
                    'booking_date' => $booking->booking_date,
                    'start_time' => $booking->start_time,
                    'end_time' => $booking->end_time,
                    'status' => $booking->status,
                    'created_at' => $booking->created_at,
                    'updated_at' => $booking->updated_at,
                ];
            });
        $totalBooking = $bookings->count();
        return response()->json([
            'success' => true,
            'message' => 'Data booking Anda dengan detail lengkap',
            'total_booking' => $bookings->count(),
            'data' => $bookings,
        ]);
    }
}
