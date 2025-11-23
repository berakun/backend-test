<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\DataAnggota;

class DataAnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventories = DataAnggota::all();

        // 2. Kembalikan data dalam format JSON
        // Kode status HTTP 200 (OK) akan otomatis diberikan
        return response()->json([
            'success' => true,
            'message' => 'Daftar data anggota berhasil diambil',
            'data' => $inventories
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $anggota = DataAnggota::all();

        // Atau, jika Anda ingin menampilkan data inventaris yang di-assign (Relasi Eager Loading)
        // $anggota = DataAnggota::with('inventaris')->get(); 

        return response()->json([
            'success' => true,
            'message' => 'Daftar data anggota berhasil diambil',
            'data' => $anggota
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'nama' => 'required|string|max:255',
        'jabatan' => 'required|string|max:100',
        'departement' => 'required|string|max:100',
    ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'message' => $validator->errors()], 400);
    }

    // Simpan data ke tabel data_anggota
    $anggota = DataAnggota::create($request->all());

    return response()->json([
        'success' => true,
        'message' => 'Data anggota berhasil ditambahkan.',
        'data' => $anggota
    ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $anggota = DataAnggota::find($id);

        // Cek apakah data ditemukan
        if (!$anggota) {
            return response()->json([
                'success' => false,
                'message' => 'Data anggota tidak ditemukan'
            ], 404);
        }

        // Atau, jika ingin menampilkan relasi inventarisnya
        // $anggota = DataAnggota::with('inventaris')->find($id);

        return response()->json([
            'success' => true,
            'message' => 'Detail data anggota berhasil diambil',
            'data' => $anggota
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Cari data anggota berdasarkan ID
        $anggota = DataAnggota::find($id);

        if (!$anggota) {
            return response()->json([
                'success' => false,
                'message' => 'Data anggota tidak ditemukan'
            ], 404);
        }

        // 1. Lakukan Validasi
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:100',
            'departement' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 400);
        }

        // 2. Simpan Perubahan
        $anggota->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data anggota berhasil diperbarui',
            'data' => $anggota
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Cari data anggota berdasarkan ID
        $anggota = DataAnggota::find($id);

        if (!$anggota) {
            return response()->json([
                'success' => false,
                'message' => 'Data anggota tidak ditemukan'
            ], 404);
        }

        // Hapus data anggota
        $anggota->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data anggota berhasil dihapus'
        ], 200);
    }
}
