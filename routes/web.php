<?php

use App\Http\Controllers\MokaController;
use Illuminate\Support\Facades\Route;

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
/*
Route::get('/', function () {
    return view('welcome');
});
*/
Auth::routes();

/*
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
*/


//index
Route::get('/moka',[App\Http\Controllers\MokaController::class, 'index'])->name('moka');

//数量チェック(ページはない)
Route::post('/moka/quantityCheck',[App\Http\Controllers\MokaController::class, 'quantityCheck'])->name('quantityCheck');

//住所入力ページ(コントローラーでredirectで移動させるのでgetメソッドにしないとだめ。POSTではエラー)
Route::get('/moka/address',[App\Http\Controllers\MokaController::class, 'address'])->name('address');

//住所入力のバリデーションの実行
Route::post('/moka/verify',[App\Http\Controllers\MokaController::class, 'verify'])->name('verify');

//ルミーズから戻る先 800を返す
Route::post('/moka/result',[App\Http\Controllers\MokaController::class, 'result'])->name('result');

//決済OKかNG
Route::get('/moka/thanks',[App\Http\Controllers\MokaController::class, 'thanks'])->name('thanks');
Route::post('/moka/thanks',[App\Http\Controllers\MokaController::class, 'thanks'])->name('thanks');

Route::get('/moka/ng',[App\Http\Controllers\MokaController::class, 'ng'])->name('ng');
Route::post('/moka/ng',[App\Http\Controllers\MokaController::class, 'ng'])->name('ng');
