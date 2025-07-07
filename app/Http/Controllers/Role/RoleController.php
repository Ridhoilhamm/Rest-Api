<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
       // Get all roles
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Role::all()
        ]);
    }

    // Create a new role
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'label' => 'nullable|string',
        ]);

        $role = Role::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil ditambahkan.',
            'data' => $role
        ], 201);
    }

    // Show specific role
    public function show($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }

    // Update role
    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role tidak ditemukan.'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
            'label' => 'nullable|string',
        ]);

        $role->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil diperbarui.',
            'data' => $role
        ]);
    }

    // Delete role
    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role tidak ditemukan.'
            ], 404);
        }

        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil dihapus.'
        ]);
    }
}
