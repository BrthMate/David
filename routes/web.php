<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
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

Route::get('/', [ SiteController::class, "homepage" ]);
Route::get('/new', [ SiteController::class, "create" ]);
Route::get('/add', [ SiteController::class, "update" ]);
Route::get('/edit/{id}', [ SiteController::class, "edit" ]);
Route::get('/delete/{id}', [ SiteController::class, "delete" ]);
Route::get('/page/{page}', [ SiteController::class, "page" ]);
Route::get("/deleteundo/{data}",[SiteController::class,"deleteundo"]);
Route::get("/select",[SiteController::class,"select"]);
Route::get("/search",[SiteController::class,"search"]);
Route::get("/getall",[SiteController::class,"getall"]);
Route::get("/import/{data}",[SiteController::class,"import"]);

