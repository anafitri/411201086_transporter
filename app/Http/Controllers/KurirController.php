<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KurirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $list = DB::table('kurir')->select('id','name','email')->get();

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
        return view('kurir.create');
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
                'name.required' => 'Nama kurir wajib diisi',
                'email.email' => 'Pastikan value yang diinput berformat email',
                'email.required' => 'Email wajib diisi',
                'email.unique' => 'Email sudah terdaftar',
                'password.required' => 'Password wajib diisi'
            ];
            $validator = Validator::make($request->all(), [
                'name'=> 'required',
                'email' => 'required|email|unique:kurir',
                'password'=> 'required'
            ], $messages);
            if ($validator->fails()) {
                $messages = $validator->messages();
                return response()->json(['message' => $messages], 400);
            }
            DB::table('kurir')->insert([
                'name' => $request->input('name'),
                'email'=> $request->input('email'),
                'password'=> bcrypt($request->input('password'))
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
        $detail = DB::table('kurir')->select('id','name','email')->where('id', $id)->first();

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
                'name.required' => 'Nama kurir wajib diisi',
                'password.required' => 'Password wajib diisi'
            ];
            $validator = Validator::make($request->all(), [
                'name'=> 'required',
                'password'=> 'required'
            ], $messages);
            if ($validator->fails()) {
                $messages = $validator->messages();
                return response()->json(['message' => $messages], 400);
            }
            DB::table('kurir')->where('id', $id)->update([
                'name' => $request->input('name'),
                'password'=> bcrypt($request->input('password'))
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
            DB::table('kurir')->where('id', $id)->delete();
            return response()->json(['message' => 'success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th, 'message' => 'failed'], 422);
        }
    }
}
