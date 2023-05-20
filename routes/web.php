<?php
use App\Http\Controllers\PengirimanController as PC;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::group([
    'middleware' => ['api','jwt.verify'],
], function(){
    Route::resource('kurir', KurirController::class);
    Route::resource('barang', BarangController::class);
    Route::resource('lokasi', LokasiController::class);
    Route::resource('pengiriman', PengirimanController::class);
    Route::put('pengiriman/approve/{id}', [PC::class, 'approve']);
});
 
Route::middleware('auth:api')->get('/user', function (Request $request) {
   return $request->user();
});