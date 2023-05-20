<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Jsonable;

class PengirimanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $limit = 10;
        if (!empty($request->limit)) {
            $limit = $request->query('limit');
        }
        $is_approved = $request->query('is_approved') == "true" ? true: false;
        $search = '';
        if (!empty($request->search)) {
            $search = $is_approved('search');
        }

        $where = [
            ['is_approved', '=', $is_approved],
            ['no_pengiriman', 'like', $search.'%']
        ];
        $list = DB::table('pengiriman')->where($where)->simplePaginate($limit);

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
        return view('pengiriman.create');
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
                'no_pengiriman.required' => 'No pengiriman wajib diisi',
                'no_pengiriman.unique' => 'No pengiriman terdaftar',
                'tanggal.required' => 'Tanggal wajib diisi',
                'tanggal.date' => 'Lengkapi tanggal dengan format yang sesuai',
                'lokasi_id.required' => 'Lokasi id wajib diisi',
                'lokasi_id.numeric' => 'Lokasi id wajib diisi dengan angka',
                'barang_id.required' => 'Barang id wajib diisi',
                'barang_id.numeric' => 'Barang id wajib diisi dengan angka',
                'kurir_id.required' => 'Kurir id wajib diisi',
                'kurir_id.numeric' => 'Kurir id wajib diisi dengan angka',
                'jumlah_barang.required' => 'Jumlah barang wajib diisi',
                'jumlah_barang.numeric' => 'Jumlah barang wajib diisi dengan angka',
                'jumlah_barang.min' => 'Lengkapi jumlah barang minimal 1',
                'harga_barang.required' => 'Harga barang wajib diisi',
                'harga_barang.numeric' => 'Harga barang wajib diisi dengan angka'
            ];
            $validator = Validator::make($request->all(), [
                'no_pengiriman'=> 'required|unique:pengiriman',
                'tanggal'=> 'required|date',
                'lokasi_id'=> 'required|numeric',
                'barang_id'=> 'required|numeric',
                'kurir_id'=> 'required|numeric',
                'jumlah_barang'=> 'required|numeric|min:1',
                'harga_barang'=>'required|numeric'
            ], $messages);
            if ($validator->fails()) {
                $messages = $validator->messages();
                return response()->json(['message' => $messages], 400);
            }

            $barang_id = $request->input('barang_id');
            $barang = DB::table('barang')->where('id',$barang_id)->first();
            if (empty($barang)) {
                return response()->json(['error' => 'Barang tidak terdaftar, mohon masukkan barang id yang sudah terdaftar', 'message' => 'failed'], 400);
            }

            $lokasi_id = $request->input('lokasi_id');
            $lokasi= DB::table('lokasi')->where('id',$lokasi_id)->first();
            if (empty($lokasi)) {
                return response()->json(['error' => 'Lokasi tidak terdaftar, mohon masukkan lokasi id yang sudah terdaftar', 'message' => 'failed'], 400);
            }

            $kurir_id = $request->input('kurir_id');
            $kurir= DB::table('lokasi')->where('id',$kurir_id)->first();
            if (empty($lokasi)) {
                return response()->json(['error' => 'Kurir tidak terdaftar, mohon masukkan kurir id yang sudah terdaftar', 'message' => 'failed'], 400);
            }

            DB::table('pengiriman')->insert([
                'no_pengiriman'=>  $request->input('no_pengiriman'),
                'tanggal'=>  $request->input('tanggal'),
                'lokasi_id'=>  $lokasi_id,
                'barang_id'=>  $barang_id,
                'kurir_id'=> $kurir_id,
                'jumlah_barang'=>  $request->input('jumlah_barang'),
                'harga_barang'=> $request->input('harga_barang'),
                'is_approved'=>  0
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
        $detail = DB::table('pengiriman')->where('id', $id)->first();

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
                'no_pengiriman.required' => 'No pengiriman wajib diisi',
                'tanggal.required' => 'Tanggal wajib diisi',
                'lokasi_id.required' => 'Lokasi id wajib diisi',
                'lokasi_id.numeric' => 'Lokasi id wajib diisi dengan angka',
                'barang_id.required' => 'Barang id wajib diisi',
                'barang_id.numeric' => 'Barang id wajib diisi dengan angka',
                'kurir_id.required' => 'Kurir id wajib diisi',
                'kurir_id.numeric' => 'Kurir id wajib diisi dengan angka',
                'jumlah_barang.required' => 'Jumlah barang wajib diisi',
                'jumlah_barang.numeric' => 'Jumlah barang wajib diisi dengan angka',
                'harga_barang.required' => 'Harga barang wajib diisi',
                'harga_barang.numeric' => 'Harga barang wajib diisi dengan angka'
            ];
            $validator = Validator::make($request->all(), [
                'no_pengiriman'=> 'required',
                'tanggal'=> 'required',
                'lokasi_id'=> 'required|numeric',
                'barang_id'=> 'required|numeric',
                'kurir_id'=> 'required|numeric',
                'jumlah_barang'=> 'required|numeric',
                'harga_barang'=>'required|numeric'
            ], $messages);
            if ($validator->fails()) {
                $messages = $validator->messages();
                return response()->json(['message' => $messages], 400);
            }
            
            $pengiriman= DB::table('pengiriman')->where('id',$id)->first();
            if (empty($pengiriman)) {
                return response()->json(['error' => 'Pengiriman tidak terdaftar, masukkan pengiriman id yang sudah terdaftar', 'message' => 'failed'], 400);
            }
            
            if (boolval($pengiriman->is_approved)) {
                return response()->json(['error' => 'Pengiriman sudah di approve tidak bisa di edit kembali', 'message' => 'failed'], 400);
            }

            $barang_id = $request->input('barang_id');
            $barang = DB::table('barang')->where('id',$barang_id)->first();
            if (empty($barang)) {
                return response()->json(['error' => 'Barang tidak terdaftar, masukkan barang id yang sudah terdaftar', 'message' => 'failed'], 400);
            }

            $lokasi_id = $request->input('lokasi_id');
            $lokasi= DB::table('lokasi')->where('id',$lokasi_id)->first();
            if (empty($lokasi)) {
                return response()->json(['error' => 'Lokasi tidak terdaftar, masukkan lokasi id yang sudah terdaftar', 'message' => 'failed'], 400);
            }

            $kurir_id = $request->input('kurir_id');
            $kurir= DB::table('lokasi')->where('id',$kurir_id)->first();
            if (empty($lokasi)) {
                return response()->json(['error' => 'Kurir tidak terdaftar, masukkan kurir id yang sudah terdaftar', 'message' => 'failed'], 400);
            }

            $validateNo= DB::table('pengiriman')->where('id',$request->input('no_pengiriman'))->first();
            if (!empty($validateNo)) {
                return response()->json(['error' => 'Nomor Pengiriman sudah terdaftar', 'message' => 'failed'], 400);
            }

            DB::table('pengiriman')->where('id',$id)->update([
                'no_pengiriman'=>  $request->input('no_pengiriman'),
                'tanggal'=>  $request->input('tanggal'),
                'lokasi_id'=>  $request->input('lokasi_id'),
                'barang_id'=>  $request->input('barang_id'),
                'kurir_id'=>  $request->input('kurir_id'),
                'jumlah_barang'=>  $request->input('jumlah_barang'),
                'harga_barang'=> $request->input('harga_barang')
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
            DB::table('pengiriman')->where('id', $id)->delete();
            return response()->json(['message' => 'success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th, 'message' => 'failed'], 422);
        }
    }

    public function approve($id)
    {
        try {
            $user = auth()->user();

            $detail = DB::table('pengiriman')->where('id', $id)->first();
            if (boolval($detail->is_approved)){
                return response()->json(['message' => 'pengiriman sudah di approve'], 400);
            }

            if ($user->id == $detail->kurir_id){
                return response()->json(['message' => 'pengiriman hanya bisa di approve oleh user lain'], 401);
            }
            DB::table('pengiriman')->where('id',$id)->update([
                'is_approved'=>  1
            ]);

            return response()->json(['message' => 'success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th, 'message' => 'failed'], 422);
        }
    }
}
