<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AriclesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

/*
Получение списка постов из таблицы articles (GET)
Uri - {host}/api/articles
*/
Route::get('/articles',[AriclesController::class,"showArticles"]);

/*
Получение 1го поста из таблицы articles по ID (GET)
Uri - {host}/api/articles/{id}
*/
Route::get('/articles/{id}',[AriclesController::class,"showArticle"]);

/*
Добавление нового поста (POST)
Uri - {host}/api/articles/{id}
*/
Route::post('/articles',[AriclesController::class,"storeAricle"]);
/*
Удаление поста по id
Uri - {host}/api/articles/{id}
*/
Route::delete('/articles/{id}', [AriclesController::class,"deleteArticle"]);