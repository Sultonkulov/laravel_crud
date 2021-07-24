<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::match(['get','psot'],'/home', 'HomeController@index')->name('home');
Route::get('/html', 'HomeController@html');
Route::get('/html/delete/{id}', 'HomeController@delete');
Route::get('/html/edit/{id}', 'HomeController@edit');

Route::get('/userfaker', function(){
$factory = Faker\Factory::create();
for($i=0; $i<=10; $i++){
    DB::insert("INSERT INTO users(name,email,password) VALUES(?, ?, ?)", [$factory->name, $factory->unique()->email, '1234']);  
} 
});