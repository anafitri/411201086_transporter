<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LokasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $list = DB::table('lokasi')->get();

        return response()->json(['data' => $list, 'message' => 'success'], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('lokasi.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $messages = [
                'kode_lokasi.required' => 'Kode lokasi wajib diisi',
                'kode_lokasi.unique' => 'Kode lokasi sudah terdaftar',
                'nama_lokasi.required' => 'Lokasi wajib diisi'
            ];
            $validator = Validator::make($request->all(), [
                'kode_lokasi'=> 'required|unique:lokasi',
                'nama_lokasi' => 'required'
            ], $messages);
            if ($validator->fails()) {
                $messages = $validator->messages();
                return response()->json(['message' => $messages], 400);
            }
            DB::table('lokasi')->insert([
                'kode_lokasi'=> $request->input('kode_lokasi'),
                'nama_lokasi' => $request->input('nama_lokasi'),
            ]);
    
            return response()->json(['message' => 'success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th, 'message' => 'failed'], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detail = DB::table('lokasi')->where('id', $id)->first();

        return response()->json(['data' => $detail], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $messages = [
                'kode_lokasi.required' => 'Kode lokasi wajib diisi',
                'kode_lokasi.unique' => 'Kode lokasi sudah terdaftar',
                'nama_lokasi.required' => 'Lokasi wajib diisi'
            ];
            $validator = Validator::make($request->all(), [
                'kode_lokasi'=> 'required',
                'nama_lokasi' => 'required'
            ], $messages);
            if ($validator->fails()) {
                $messages = $validator->messages();
                return response()->json(['message' => $messages], 400);
            }

            $lokasi = DB::table('lokasi')->where('kode_lokasi', $request->input('kode_lokasi'))->first();
            if (!empty($lokasi)) {
                if ($lokasi->id != $id) {
                    return response()->json(['message' => 'Kode barang sudah terdaftar'], 400);
                }
            }
            
            DB::table('lokasi')->where('id',$id)->update([
                'kode_lokasi'=> $request->input('kode_lokasi'),
                'nama_lokasi' => $request->input('nama_lokasi')
            ]);

            return response()->json(['message' => 'success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th, 'message' => 'failed'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            DB::table('lokasi')->where('id', $id)->delete();
            return response()->json(['message' => 'success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th, 'message' => 'failed'], 422);
        }
    }
}
