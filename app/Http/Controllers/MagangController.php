<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Magang;

class MagangController extends Controller
{
    public function index()
    {
        // Mengambil semua data magang termasuk kolom 'universitas'
        $magang = Magang::all();

        // Mengirim data magang ke view 'magang.index'
        return view('magang.index', compact('magang'));
    }

    public function create()
    {
        return view('magang.create');
    }

    public function store(Request $request)
    {
        // Validasi data yang diterima dari form
        $request->validate([
            'universitas' => 'required|string|max:255',
            'jumlah_anak' => 'required|integer',
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'required|date',
        ]);

        // Menyimpan data magang ke database
        Magang::create([
            'universitas' => $request->universitas,
            'jumlah_anak' => $request->jumlah_anak,
            'tanggal_masuk' => $request->tanggal_masuk,
            'tanggal_keluar' => $request->tanggal_keluar,
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('magang.index')->with('success', 'Data magang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        // Mencari data magang berdasarkan ID
        $magang = Magang::findOrFail($id);

        // Mengirim data magang ke view 'magang.edit'
        return view('magang.edit', compact('magang'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data yang diterima dari form
        $request->validate([
            'universitas' => 'required|string|max:255',
            'jumlah_anak' => 'required|integer',
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'required|date',
        ]);

        // Mencari data magang berdasarkan ID
        $magang = Magang::findOrFail($id);

        // Memperbarui data magang
        $magang->update([
            'universitas' => $request->universitas,
            'jumlah_anak' => $request->jumlah_anak,
            'tanggal_masuk' => $request->tanggal_masuk,
            'tanggal_keluar' => $request->tanggal_keluar,
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('magang.index')->with('success', 'Data magang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Mencari dan menghapus data magang berdasarkan ID
        $magang = Magang::findOrFail($id);
        $magang->delete();

        // Mengirim response sukses setelah menghapus data
        return redirect()->route('magang.index')->with('success', 'Data magang berhasil dihapus.');
    }
}
