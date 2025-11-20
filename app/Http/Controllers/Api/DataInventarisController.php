<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataInventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataInventarisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventories = DataInventaris::all();

        // 2. Kembalikan data dalam format JSON
        // Kode status HTTP 200 (OK) akan otomatis diberikan
        return response()->json([
            'success' => true,
            'message' => 'Daftar data inventaris berhasil diambil',
            'data' => $inventories
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inventaris_id' => 'required|string|unique:data_inventaris',
            'barang' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'spesifikasi' => 'required|string|max:255',
            'status' => 'required|in:Baik,Rusak,Tidak Dipakai,Dilelang',
            'department' => 'required|string|max:100',
            'serial_number' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id', // Pastikan user_id ada di tabel users
        ]);

        // Jika Validasi Gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422); // Status 422 Unprocessable Entity
        }

        // 2. Buat Data Baru
        $inventaris = DataInventaris::create($validator->validated());

        // 3. Kembalikan Respon Sukses
        return response()->json([
            'success' => true,
            'message' => 'Data inventaris berhasil ditambahkan',
            'data' => $inventaris
        ], 201); // Status 201 Created
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $inventaris = DataInventaris::find($id);

        if (!$inventaris) {
            return response()->json([
                'success' => false,
                'message' => 'Data inventaris tidak ditemukan'
            ], 404);
        }

        // 2. Validasi Data Masukan
        $validator = Validator::make($request->all(), [
            'inventaris_id' => 'required|string|unique:data_inventaris,inventaris_id,' . $id,
            'barang' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|string|max:100',
            'spesifikasi' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:Baik,Rusak,Tidak Dipakai,Dilelang',
            'department' => 'sometimes|required|string|max:100',
            'serial_number' => 'nullable|string|max:255',
        ]);

        // Jika Validasi Gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // 3. Perbarui Data
        // Gunakan fill() dan save() untuk menerapkan perubahan
        $inventaris->update($validator->validated());

        // 4. Kembalikan Respon Sukses
        return response()->json([
            'success' => true,
            'message' => 'Data inventaris berhasil diperbarui',
            'data' => $inventaris
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $inventaris = DataInventaris::find($id);

        if (!$inventaris) {
            return response()->json([
                'success' => false,
                'message' => 'Data inventaris tidak ditemukan'
            ], 404); // Status 404 Not Found
        }

        // 2. Hapus Data
        $inventaris->delete();

        // 3. Kembalikan Respon Sukses
        return response()->json([
            'success' => true,
            'message' => 'Data inventaris berhasil dihapus'
        ], 200);
    }
}
