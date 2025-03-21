<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NamaBarang;
use App\Models\JenisBarang;
use App\Models\SatuanBarang;
use App\Models\NamaCabang;
use App\Models\AlamatCabang;
use App\Models\PenanggungJawabCabang;


class MasterDataController extends Controller
{
    //menampilkan data
    public function namaBarang()
{
    $data = NamaBarang::with(['jenisBarang', 'satuanBarang'])->get();
    $jenis_barang = JenisBarang::all();
    $satuan_barang = SatuanBarang::all();
    
    return view('masterdata.nama-barang', compact('data', 'jenis_barang', 'satuan_barang'));
}

    public function jenisBarang()
    {
        $data = JenisBarang::all();
        return view('masterdata.jenis-barang', compact('data'));
    }

    public function satuanBarang()
    {
        $data = SatuanBarang::all();
        return view('masterdata.satuan-barang', compact('data'));
    }

    //tambah data
    public function tambahNamaBarang(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|string|max:255|unique:nama_barangs',
            'nama_barang' => 'required|string|max:255',
            'jenis_barang' => 'required|string|max:255',
            'satuan_barang' => 'required|string|max:255',
        ]);

        NamaBarang::create($request->except('_token'));
        return redirect('/masterdata/nama-barang')->with('success', 'Data berhasil ditambahkan');
    }

    public function tambahJenisBarang(Request $request)
{
    $request->validate([
        'jenis' => 'required|string|max:255'
    ]);

    JenisBarang::create([
        'jenis' => $request->jenis
    ]);

    return redirect()->back()->with('success', 'Jenis barang berhasil ditambahkan');
}

    public function tambahSatuanBarang(Request $request)
{
    $request->validate([
        'satuan' => 'required|string|max:255'
    ]);

    SatuanBarang::create([
        'satuan' => $request->satuan
    ]);

    return redirect()->back()->with('success', 'Satuan barang berhasil ditambahkan');
}

// edit data dan delete data nama barang
public function getBarang($kode_barang)
{
    $barang = NamaBarang::where('kode_barang', $kode_barang)->first();
    return response()->json($barang);
}

public function updateBarang(Request $request, $kode_barang)
{
    $barang = NamaBarang::where('kode_barang', $kode_barang)->first();
    $barang->update($request->all());
    return redirect()->back()->with('success', 'Data berhasil diupdate');
}

public function deleteBarang($kode_barang)
{
    NamaBarang::where('kode_barang', $kode_barang)->delete();
    return redirect()->back()->with('success', 'Data berhasil dihapus');
}
// .. selesai ..

// ... update dan delete jenis data ...

// edit dan delete satuan barang


public function getJenisBarang($id)
{
    $jenis = JenisBarang::findOrFail($id);
    return response()->json($jenis);
}

public function updateJenisBarang(Request $request, $id)
{
    $jenis = JenisBarang::findOrFail($id);
    $jenis->update([
        'jenis' => $request->jenis
    ]);
    return redirect()->back()->with('success', 'Jenis barang berhasil diupdate');
}

public function deleteJenisBarang($id)
{
    JenisBarang::findOrFail($id)->delete();
    return redirect()->back()->with('success', 'Jenis barang berhasil dihapus');
}

// ... update dan delete jenis data ...

// edit dan delete satuan barang
public function getSatuanBarang($id)
{
    $satuan = SatuanBarang::findOrFail($id);
    return response()->json($satuan);
}

public function updateSatuanBarang(Request $request, $id)
{
    $satuan = SatuanBarang::findOrFail($id);
    $satuan->update([
        'satuan' => $request->satuan
    ]);
    return redirect()->back()->with('success', 'Satuan barang berhasil diupdate');
}

public function deleteSatuanBarang($id)
{
    SatuanBarang::findOrFail($id)->delete();
    return redirect()->back()->with('success', 'Satuan barang berhasil dihapus');
}

//cabang
public function namaCabang()
{
    $data = NamaCabang::all();
    return view('masterdata.nama-cabang', compact('data'));
}

public function tambahNamaCabang(Request $request)
{
    $request->validate([
        'kode_cabang' => 'required|string|max:10|unique:nama_cabangs',
        'nama_cabang' => 'required|string|max:255'
    ]);

    NamaCabang::create($request->all());
    return redirect()->back()->with('success', 'Data cabang berhasil ditambahkan');
}

public function getNamaCabang($id)
{
    $cabang = NamaCabang::findOrFail($id);
    return response()->json($cabang);
}

public function updateNamaCabang(Request $request, $id)
{
    $request->validate([
        'kode_cabang' => 'required|string|max:10|unique:nama_cabangs,kode_cabang,'.$id,
        'nama_cabang' => 'required|string|max:255'
    ]);

    $cabang = NamaCabang::findOrFail($id);
    $cabang->update($request->all());
    return redirect()->back()->with('success', 'Data cabang berhasil diupdate');
}

public function deleteNamaCabang($id)
{
    NamaCabang::findOrFail($id)->delete();
    return redirect()->back()->with('success', 'Data cabang berhasil dihapus');
}

//alamat Cabang
public function alamatCabang()
{
    $data = AlamatCabang::with('namaCabang')->get();
    $nama_cabang = NamaCabang::all();
    return view('masterdata.alamat-cabang', compact('data', 'nama_cabang'));
}

public function tambahAlamatCabang(Request $request)
{
    $request->validate([
        'nama_cabang_id' => 'required|exists:nama_cabangs,id',
        'alamat' => 'required|string',
        'kota' => 'required|string|max:255',
        'provinsi' => 'required|string|max:255',
        'kode_pos' => 'required|string|size:5'
    ]);

    AlamatCabang::create($request->all());
    return redirect()->back()->with('success', 'Alamat cabang berhasil ditambahkan');
}

public function getAlamatCabang($id)
{
    $alamat = AlamatCabang::findOrFail($id);
    return response()->json($alamat);
}

public function updateAlamatCabang(Request $request, $id)
{
    $request->validate([
        'nama_cabang_id' => 'required|exists:nama_cabangs,id',
        'alamat' => 'required|string',
        'kota' => 'required|string|max:255',
        'provinsi' => 'required|string|max:255',
        'kode_pos' => 'required|string|size:5'
    ]);

    $alamat = AlamatCabang::findOrFail($id);
    $alamat->update($request->all());
    return redirect()->back()->with('success', 'Alamat cabang berhasil diupdate');
}

public function deleteAlamatCabang($id)
{
    AlamatCabang::findOrFail($id)->delete();
    return redirect()->back()->with('success', 'Alamat cabang berhasil dihapus');
}

public function penanggungJawabCabang()
{
    $data = PenanggungJawabCabang::with('namaCabang')->get();
    $nama_cabang = NamaCabang::all();
    return view('masterdata.penanggung-jawab-cabang', compact('data', 'nama_cabang'));
}

public function tambahPenanggungJawabCabang(Request $request)
{
    $request->validate([
        'nama_cabang_id' => 'required|exists:nama_cabangs,id',
        'nama_penanggung_jawab' => 'required|string|max:255',
        'no_telepon_cs' => 'required|string|max:15',
        'no_telepon_pj' => 'required|string|max:15'
    ]);

    PenanggungJawabCabang::create($request->all());
    return redirect()->back()->with('success', 'Data penanggung jawab berhasil ditambahkan');
}

public function getPenanggungJawabCabang($id)
{
    $pj = PenanggungJawabCabang::findOrFail($id);
    return response()->json($pj);
}

public function updatePenanggungJawabCabang(Request $request, $id)
{
    $request->validate([
        'nama_cabang_id' => 'required|exists:nama_cabangs,id',
        'nama_penanggung_jawab' => 'required|string|max:255',
        'no_telepon_cs' => 'required|string|max:15',
        'no_telepon_pj' => 'required|string|max:15'
    ]);

    $pj = PenanggungJawabCabang::findOrFail($id);
    $pj->update($request->all());
    return redirect()->back()->with('success', 'Data penanggung jawab berhasil diupdate');
}

public function deletePenanggungJawabCabang($id)
{
    PenanggungJawabCabang::findOrFail($id)->delete();
    return redirect()->back()->with('success', 'Data penanggung jawab berhasil dihapus');
}

}
