<?php

/**
 * Page d'accueil du site
 */
Route::get('/', function () {
	return view('marketplace')->with([
		'title' => 'Accueil.',
		'type' => 'website',
		'url'  => getenv('APP_URL'),
		'image' => asset('/storage/bobby_images/icons/logo-beta.svg')
	]);
})->name('marketplace')->middleware('session');

Route::any('adminer', '\Miroc\LaravelAdminer\AdminerController@index');
