<?php

namespace App\Http\Controllers;

use App\Models\pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->search;

        $pelanggans = Pelanggan::orderBy('id')
        ->when($search, function($q, $search){
            return $q->where('nama','like',"%{$search}%");
        })
        ->paginate();

        if ($search) $pelanggans->appends(['search'=>$search]);

        return view('pelanggan.index',[
            'pelanggans'=>$pelanggans
        ]);
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'=>['required','max:100'],
            'alamat'=>['nullable','max:500'],
            'nomor_tlp'=>['nullable','max:14']
        ]);

        Pelanggan::create($request->all());

        return redirect()->route('pelanggan.index')->with('store','success');
    }

    public function show(pelanggan $pelanggan)
    {
        abort(404);
    }

    public function edit(pelanggan $pelanggan)
    {
        return view('pelanggan.edit',[
            'pelanggan'=>$pelanggan
        ]);
    }

    public function update(Request $request, pelanggan $pelanggan)
    {
        $request->validate([
            'nama'=>['required','max:100'],
            'alamat'=>['nullable','max:500'],
            'nomor_tlp'=>['nullable','max:14']
        ]);

        $pelanggan->update($request->all());

        return redirect()->route('pelanggan.index')->with('update','success');
    }
    
    public function autocomplete(Request $request)
    {
        $term = $request->term;

        $pelanggans = Pelanggan::where('nama', 'like', "%{$term}%")->get();

        $data = [];
        foreach ($pelanggans as $pelanggan) {
            $data[] = [
                'id' => $pelanggan->id,
                'nama' => $pelanggan->nama
            ];
        }

        return response()->json($data);
    }
    public function destroy(pelanggan $pelanggan)
    {
        $pelanggan->delete();

        return back()->with('destroy','success');
    }
}
