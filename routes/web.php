<?php
	Route::any('profile/{id}', 'ProfileController@show')->name('profile');
	Route::post('profile/{id}', 'ProfileController@reply');
	Route::any('com/{id}', 'ProfileController@getCommentsByProfile');
	Route::delete('/delete', 'ProfileController@delete');
	Route::any('profile/{id}/all', 'ProfileController@showAll');
	Route::any('myprofile/{id}', 'ProfileController@showProfile');
	// Маршруты аутентификации...
	Route::get('auth/login', 'Auth\AuthController@getLogin');
	Route::post('auth/login', 'Auth\AuthController@postLogin');
	Route::get('auth/logout', 'Auth\AuthController@getLogout');

	// Маршруты регистрации...
	Route::get('auth/register', 'Auth\AuthController@getRegister');
	Route::post('auth/register', 'Auth\AuthController@postRegister');
	Auth::routes();
	Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
?>
