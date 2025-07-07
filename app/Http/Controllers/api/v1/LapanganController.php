<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LapanganController extends Controller
{
    public function store(Request $request)
    {
        if (!checkRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak:Hanya Admin'
            ], 403);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'thumbnail' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'detail_photos.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }


        // Simpan thumbnail
        $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');

        // Simpan banyak detail foto
        $detailPhotos = [];
        if ($request->hasFile('detail_photos')) {
            foreach ($request->file('detail_photos') as $photo) {
                $path = $photo->store('details', 'public');
                $detailPhotos[] = $path;
            }
        }

        $field = Lapangan::create([
            'name' => $request->name,
            'description' => $request->description,
            'thumbnail' => $thumbnailPath,
            'detail_photos' => $detailPhotos,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Lapangan berhasil ditambahkan',
            'data' => $field
        ], 201);
    }

    public function index()
    {
        if (!auth('sanctum')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak ditemukan atau belum login.',
            ], 401);
        }
        if (!checkRole('user', 'admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak harap Login Dahulu'
            ], 403);
        }

        $fields = Lapangan::all();

        return response()->json([
            'success' => true,
            'message' => 'Data semua lapangan',
            'data' => $fields
        ]);
    }


    public function show($id)
    {
        // Cek jika user belum login
        if (!auth('sanctum')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Silakan login terlebih dahulu.',
            ], 401); // HTTP 401: Unauthorized
        }

        $field = Lapangan::find($id);

        if (!$field) {
            return response()->json([
                'success' => false,
                'message' => 'Lapangan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail lapangan',
            'data' => $field
        ]);
    }


    public function destroy($id)
    {
        if (!checkRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak: Delete Data Hanya Admin'
            ], 403);
        }
        $field = Lapangan::find($id);

        if (!$field) {
            return response()->json([
                'success' => false,
                'message' => 'Lapangan tidak ditemukan'
            ], 404);
        }

        $deletedData = $field->toArray();

        $field->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lapangan berhasil dihapus (soft delete)',
            'deleted_data' => $deletedData
        ]);
    }

    public function getDeletedFields()
    {
        $fields = Lapangan::onlyTrashed()->get();

        return response()->json([
            'success' => true,
            'message' => 'Data lapangan yang sudah dihapus (soft delete)',
            'data' => $fields
        ]);
    }
}
