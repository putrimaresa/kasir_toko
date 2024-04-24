@extends('layouts.main',['title'=>'Laporan'])
@section('title-content')
    <i class="fas fa-print mr-2"></i> 
    Laporan
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-6 col-xl-4">
            <form target="_blank" method="GET"  action="{{ route('laporan.harian') }}" class="card card-orange card-outline">
                <div class="card-header">
                    <h5 class="card-title">Buat Laporan Harian</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" class="form-control"  />
                    </div>

                    <div class="form-group row">
                        <div class="col">
                            <label>Kasir</label>
                            <select name="user_id" class="form-control">
                                <option value="">Pilih Kasir:</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-print mr-2"></i> Cetak
                    </button>               
                </div>
            </form>
        </div>

        <div class="col-lg-6 col-xl-4">
            <form target="_blank" method="GET"  action="{{ route('laporan.bulanan') }}" class="card card-orange card-outline">
                <div class="card-header">
                    <h5 class="card-title"> Buat Laporan Perbulan</h5>
                </div>
        
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col">
                            <label>Bulan</label>
                            @php
                                $pilihan = ['Pilih Bulan:','Januari','Februari','Maret','April','Mei',
                                'Juni','Juli','Agustus','September','Oktober','November','Desember'];
                            @endphp
                            <select name="bulan" class="form-control">
                                @foreach ($pilihan as $key => $value )
                                    <option value="{{ $key? $key : '' }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label>Tahun</label>
                        <select name="tahun" class="form-control">
                            <option value=" ">Pilih Tahun:</option>
                            @php
                                $tahun = date('Y');
                                $max = $tahun - 5;
                            @endphp
                            @for ($tahun; $tahun > $max ; $tahun--)
                                <option value="{{ $tahun }}">{{ $tahun }}</option>
                            @endfor
                        </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col">
                            <label>Pelanggan</label>
                            <input type="text" name="pelanggan_nama" class="form-control" placeholder="Pilih Pelanggan atau ketik nama pelanggan..." value="{{ old('pelanggan_nama') }}" />
                            <input type="hidden" name="pelanggan_id" id="pelanggan_id" value="{{ old('pelanggan_id') }}" />
                        </div>
                    </div>
                    
                   
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-block btn-outline-orange">Cetak Laporan</button>
                </div>
            </form>
        </div>
    </div>
@endsection 
@push('script')
<div class="form-group row">
    <div class="col">
        <label>Pelanggan</label>
        <input type="text" name="pelanggan_nama" class="form-control" placeholder="Pilih Pelanggan atau ketik nama pelanggan..." value="{{ old('pelanggan_nama') }}" />
        <input type="hidden" name="pelanggan_id" id="pelanggan_id" value="{{ old('pelanggan_id') }}" />
    </div>
</div>
<script>
$(document).ready(function() {
    $('input[name="pelanggan_nama"]').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "{{ route('pelanggan.autocomplete') }}",
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            $('#pelanggan_id').val(ui.item.id);
            $('input[name="pelanggan_nama"]').val(ui.item.nama);
            return false;
        }
    });
});
</script>
@endpush