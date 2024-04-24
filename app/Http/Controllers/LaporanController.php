<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\pelanggan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('laporan.form', ['users' => $users]); 
    }

    public function harian(Request $request)
    {
        $userId = $request->user_id;

        $penjualan = Penjualan::join('users', 'users.id', 'penjualans.user_id')
            ->join('pelanggans', 'pelanggans.id', 'penjualans.pelanggan_id')
            ->whereDate('tanggal', $request->tanggal)
            ->where('status', '!=', 'batal') 
            ->where(function ($query) use ($userId) {
                if ($userId) {
                    $query->where('penjualans.user_id', $userId);
                }
            })     
            ->select('penjualans.*', 'pelanggans.nama as nama_pelanggan', 'users.nama as nama_kasir')
            ->orderBy('id')
            ->get();

        return view('laporan.harian', ['penjualan'=> $penjualan]);
    } 

    public function bulanan(Request $request)
    {
        $pelangganId = $request->pelanggan_id;

        $penjualan = Penjualan::select(            
            DB::raw('COUNT(id) as jumlah_transaksi'),
            DB::raw('SUM(total) as jumlah_total'),
            DB::raw("DATE_FORMAT(tanggal, '%d/%m/%Y') tgl")
        )        
            ->whereMonth('tanggal', $request->bulan)
            ->whereYear('tanggal', $request->tahun)
            ->where('status', '!=', 'batal') 
            ->when($pelangganId, function($query) use ($pelangganId) {
                return $query->where('pelanggan_id', $pelangganId);
            })
            ->groupBy('tgl')
            ->get();

        $nama_bulan = [
            'Januari','Februari','Maret','April','Mei',
            'Juni','Juli','Agustus','September','Oktober','November','Desember'
        ];

        $bulan = isset($nama_bulan[$request->bulan - 1]) ? $nama_bulan[$request->bulan - 1] : null;

        return view('laporan.bulanan', [
            'penjualan'=> $penjualan,
            'bulan'=> $bulan
        ]);
    }

}