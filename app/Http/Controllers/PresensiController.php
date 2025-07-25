<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; // <-- Import facade PDF


class PresensiController extends Controller
{
    // public function index()
    // {
    //     // Ambil presensi terakhir untuk user yang sedang login
    //     $latestPresensi = Presensi::where('user_id', Auth::id())
    //         ->latest()
    //         ->first();

    //     // Tentukan apakah user sudah clock-in dan clock-out hari ini
    //     $hasClockedIn = $latestPresensi && $latestPresensi->clock_in && $latestPresensi->created_at->isToday();
    //     $hasClockedOut = $latestPresensi && $latestPresensi->clock_out && $latestPresensi->created_at->isToday();

    //     // Ambil presensi untuk hari ini
    //     $todayPresensi = Presensi::where('user_id', Auth::id())
    //         ->whereDate('created_at', Carbon::today('Asia/Jakarta'))
    //         ->first();

    //     // Ambil semua presensi untuk ditampilkan
    //     $presensi = Presensi::where('user_id', Auth::id())->get();

    //     return view('dashboard', compact('presensi', 'todayPresensi', 'hasClockedIn', 'hasClockedOut'));
    // }

    // public function clockIn(Request $request)
    // {
    //     $existingPresensi = Presensi::where('user_id', Auth::id())
    //         ->whereDate('created_at', Carbon::today('Asia/Jakarta'))
    //         ->first();

    //     if ($existingPresensi) {
    //         // Jika sudah ada clockIn atau izin hari ini, tolak clockIn
    //         return redirect()->route('dashboard')->with('error', 'Anda sudah melakukan presensi atau izin hari ini.');
    //     }

    //     $presensi = new Presensi();
    //     $presensi->user_id = Auth::id();
    //     $presensi->clock_in = Carbon::now('Asia/Jakarta');
    //     $presensi->status = 'Hadir';
    //     $presensi->save();

    //     return redirect()->route('dashboard')->with('success', 'Clock-in berhasil.');
    // }


    public function index()
    {
        // 1. Ambil data presensi atau izin untuk user yang login hari ini
        $todayPresensi = Presensi::where('user_id', Auth::id())
            ->whereDate('created_at', Carbon::today('Asia/Jakarta'))
            ->first();

        // 2. Buat variabel boolean untuk mengecek apakah sudah ada aksi (check-in atau izin)
        // Variabel ini akan bernilai true jika $todayPresensi tidak null
        $hasTakenActionToday = $todayPresensi !== null;

        // 3. Kirim kedua variabel ke view
        // Ambil semua presensi untuk ditampilkan
        $presensi = Presensi::where('user_id', Auth::id())->get();
        return view('dashboard', [
            'todayPresensi' => $todayPresensi,
            'hasTakenActionToday' => $hasTakenActionToday,
            'presensi' => $presensi,
        ]);
    }


    public function clockIn(Request $request)
    {

        // Validasi input dari form modal
        $request->validate([
            'lokasi' => 'required|string',
            'foto_lokasi' => 'required|image|max:2048', // Validasi file gambar
            'catatan' => 'nullable|string',
        ]);

        // Cek apakah hari ini sudah clock in
        $todayPresensi = Presensi::where('user_id', Auth::id())
                            ->whereDate('created_at', Carbon::today())
                            ->first();

        if ($todayPresensi) {
            return response()->json(['error' => 'Anda sudah melakukan check-in hari ini.'], 422);
        }

        // Simpan foto
        $path = $request->file('foto_lokasi')->store('public/presensi_photos');

        // Buat record presensi baru
        Presensi::create([
            'user_id' => Auth::id(),
            'clock_in' => now(),
            'lokasi' => $request->lokasi,
            'foto_lokasi' => $path, // Simpan path fotonya
            'status' => 'Hadir',
            'catatan' => $request->catatan,
        ]);

        return response()->json(['message' => 'Check-in berhasil!']);
    }

    public function clockOut(Request $request)
    {

        // Cek apakah sudah ada izin atau clockIn hari ini
        $presensi = Presensi::where('user_id', Auth::id())
            ->whereDate('created_at', Carbon::today('Asia/Jakarta'))
            ->first();

        if (!$presensi || $presensi->clock_out || $presensi->status !== 'Hadir') {
            // Jika belum clockIn, sudah clockOut, atau status bukan 'Hadir', tolak clockOut
            return redirect()->route('dashboard')->with('error', 'Anda tidak dapat melakukan clock out saat ini.');
        }

        $presensi->clock_out = Carbon::now('Asia/Jakarta');
        $presensi->save();

        return redirect()->route('dashboard')->with('success', 'Clock-Out berhasil.');
    }

    public function izinForm()
    {
        return view('presensi.izin');
    }

    public function izinSubmit(Request $request)
    {
        // Cek apakah sudah ada presensi atau izin hari ini
        $existingPresensi = Presensi::where('user_id', Auth::id())
            ->whereDate('created_at', Carbon::today('Asia/Jakarta'))
            ->first();

        if ($existingPresensi) {
            // Jika sudah ada presensi atau izin hari ini, tolak izin baru
            return redirect()->route('dashboard')->with('error', 'Anda sudah melakukan presensi atau izin hari ini.');
        }

        $request->validate([
            'keterangan' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        $presensi = new Presensi();
        $presensi->user_id = Auth::id();
        $presensi->status = $request->keterangan;
        $presensi->catatan = $request->catatan;
        $presensi->save();

        return redirect()->route('dashboard');
    }

     public function generateRekapPDF()
    {
        // 1. Ambil data yang ingin diekspor
        // Pastikan query ini sama dengan yang menampilkan data di dashboard
        $presensi = Presensi::with('user')->orderBy('created_at', 'desc')->get();

        // 2. Load view PDF dan teruskan datanya
        // Kita akan membuat view 'presensi_rekap_pdf.blade.php' di langkah berikutnya
        $pdf = PDF::loadView('presensi_rekap_pdf', ['presensi' => $presensi]);

        // 3. Download file PDF dengan nama tertentu
        return $pdf->download('rekap-presensi-' . date('Y-m-d') . '.pdf');
    }
}
