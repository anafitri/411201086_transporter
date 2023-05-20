<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $list = DB::table('barang')->get();

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
        return view('barang.create');
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
                'kode_barang.required' => 'Kode barang wajib diisi',
                'kode_barang.unique' => 'Kode barang sudah terdaftar',
                'nama_barang.required' => 'Nama barang wajib diisi',
                'stok_barang.required' => 'Stok barang wajib diisi',
                'stok_barang.numeric' => 'Stok barang dengan angka',
                'harga_barang.required' => 'Harga barang wajib diisi',
                'harga_barang.numeric' => 'Harga barang wajib diisi dengan angka'
            ];
            $validator = Validator::make($request->all(), [
                'kode_barang'=> 'required|unique:barang',
                'nama_barang' => 'required',
                'stok_barang'=>'required|numeric',
                'harga_barang'=>'required|numeric'
            ], $messages);
            if ($validator->fails()) {
                $messages = $validator->messages();
                return response()->json(['message' => $messages], 400);
            }
            DB::table('barang')->insert([
                'kode_barang'=> $request->input('kode_barang'),
                'nama_barang' => $request->input('nama_barang'),
                'deskripsi' => $request->input('deskripsi'),
                'stok_barang'=>$request->input('stok_barang'),
                'harga_barang'=>$request->input('harga_barang')
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
        $detail = DB::table('barang')->where('id', $id)->first();

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
                'kode_barang.required' => 'Kode barang wajib diisi',
                'kode_barang.unique' => 'Kode barang sudah terdaftar',
                'nama_barang.required' => 'Nama barang wajib diisi',
                'stok_barang.required' => 'Stok barang wajib diisi',
                'stok_barang.numeric' => 'Stok barang dengan angka',
                'harga_barang.required' => 'Harga barang wajib diisi',
                'harga_barang.numeric' => 'Harga barang wajib diisi dengan angka'
            ];
            $validator = Validator::make($request->all(), [
                'kode_barang'=> 'required',
                'nama_barang' => 'required',
                'stok_barang'=>'required|numeric',
                'harga_barang'=>'required|numeric'
            ], $messages);
            if ($validator->fails()) {
                $messages = $validator->messages();
                return response()->json(['message' => $messages], 400);
            }

            $barang = DB::table('barang')->where('kode_barang', $request->input('kode_barang'))->first();
            if (!empty($barang)) {
                if ($barang->id != $id) {
                    return response()->json(['message' => 'Kode barang sudah terdaftar'], 400);
                }
            }
            
            DB::table('barang')->where('id',$id)->update([
                'nama_barang' => $request->input('nama_barang'),
                'kode_barang' => $request->input('kode_barang'),
                'stok_barang' =>$request->input('stok_barang'),
                'deskripsi' =>$request->input('deskripsi'),
                'harga_barang' =>$request->input('harga_barang')
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
            DB::table('barang')->where('id', $id)->delete();
            return response()->json(['message' => 'success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th, 'message' => 'failed'], 422);
        }
    }
}
