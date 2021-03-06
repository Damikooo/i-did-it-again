<?php
	Route::get('profile/{id}', 'ProfileController@show')->name('profile');
	Route::post('profile/{id}', 'ProfileController@reply');
	Route::any('profile/{id}/all', 'ProfileController@showAll');
	Route::any('com/{id}', 'ProfileController@getCommentsByProfile');
	Route::delete('/delete', 'ProfileController@delete');
	Route::any('myprofile/{id}', 'ProfileController@showProfile');

	Route::group(['middleware' => ['auth', 'book']], function () {
		Route::get('book/{id}/', 'ProfileController@checkBook')->name('book');
	});
	Route::group(['middleware' => ['library']], function () {
		Route::post('library/{id}', 'ProfileController@shareBook');
		Route::post('bookwrite', 'ProfileController@writeBook');
		Route::post('bookremove', 'ProfileController@remove');
	});
	Route::get('library/{id}', 'ProfileController@showLibrary')->middleware(['auth', 'lib'])->name('library');
	Route::post('lib', 'ProfileController@shareLibrary')->middleware('auth');
	Route::post('bookedit', 'ProfileController@edit')->middleware('edit');

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
