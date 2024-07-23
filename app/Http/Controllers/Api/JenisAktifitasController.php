<?php

namespace App\Http\Controllers\Api;

use App\Exports\JenisAktifitasExport;
use App\Http\Controllers\Controller;
use App\Imports\JenisAktifitasImport;
use App\Models\JenisAktifitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class JenisAktifitasController extends Controller
{
    public function index()
    {
        $jenisAktifitas = JenisAktifitas::orderBy('kode_jenis_aktifitas', 'asc')->paginate(10);
        return response()->json(['status' => true, 'data' => $jenisAktifitas]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_jenis_aktifitas' => 'required|unique:jenis_aktifitas',
            'nama_jenis_aktifitas' => 'required',
            'flag_tab_menu_personal' => 'required|in:active,inactive',
            'batas_waktu_kunjungan' => 'required|integer',
            'batas_maksimal_umur_progress' => 'required|integer',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation Error', 'data' => $validator->errors()]);
        }

        $jenisAktifitas = JenisAktifitas::create($request->all());
        return response()->json(['status' => true, 'message' => 'Jenis Aktifitas created successfully', 'data' => $jenisAktifitas]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:jenis_aktifitas,id',
            'kode_jenis_aktifitas' => 'required|unique:jenis_aktifitas,kode_jenis_aktifitas,' . $request->id,
            'nama_jenis_aktifitas' => 'required',
            'flag_tab_menu_personal' => 'required|in:active,inactive',
            'batas_waktu_kunjungan' => 'required|integer',
            'batas_maksimal_umur_progress' => 'required|integer',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation Error', 'data' => $validator->errors()]);
        }

        $jenisAktifitas = JenisAktifitas::findOrFail($request->id);
        $jenisAktifitas->update($request->all());
        return response()->json(['status' => true, 'message' => 'Jenis Aktifitas updated successfully', 'data' => $jenisAktifitas]);
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:jenis_aktifitas,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation Error', 'data' => $validator->errors()]);
        }

        $jenisAktifitas = JenisAktifitas::findOrFail($request->id);
        $jenisAktifitas->delete();
        return response()->json(['status' => true, 'message' => 'Jenis Aktifitas deleted successfully']);
    }

    public function bulkUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation Error', 'data' => $validator->errors()]);
        }

        try {
            Excel::import(new JenisAktifitasImport, $request->file('file'));
            return response()->json(['status' => true, 'message' => 'File Imported Successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function export()
    {
        return Excel::download(new JenisAktifitasExport, 'jenis_aktifitas.xlsx');
    }


    public function bulkUploadDetails($id)
    {
        // Implement your bulk upload details logic here
    }
}
