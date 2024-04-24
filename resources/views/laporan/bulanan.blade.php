@extends('layouts.laporan',['title'=>'Laporan'])
@section('content')
<div class="container-fluid">
    <h1 class="text-center">
      Laporan Bulanan
    </h1>
    <p>Bulan : {{ $bulan }} {{ request()->tahun }}</p>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jumlah Transaksi</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penjualan as $key => $row)
            <tr>
                <td>{{ $key + 1}}</td>
                <td>{{ $row->tgl}}</td>
                <td>{{ $row->jumlah_transaksi}}</td>
                <td>{{ number_format($row->jumlah_total,0,',','.')  }}</td>
            </tr>
            @endforeach
           
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3"> JumlahTotal</th>
                <th>{{ number_format($penjualan->sum('jumlah_total'),0,',','.')  }}</th>
            </tr>
        </tfoot>
    </table>
</div>
@endsection