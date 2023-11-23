<?php


use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestController;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\App;


Route::get('/test', [TestController::class, 'test']);

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registering'])->name('registering');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


// tạo provider đến oauth của github
Route::get('/auth/redirect/{provider}', function ($provider) {
    return Socialite::driver($provider)->redirect();
})->name('auth.redirect');

// sau khi xác thực quyền sẽ trả ra thông tin của
Route::get('/auth/callback/{provider}', [AuthController::class, 'callback'])->name('auth.callback');


Route::get('/', function () {
    return view('layout.master');
})->name('welcome');

// change languages
Route::get('/languages/{locale}', function (string $locale) {
    if (!in_array($locale, config('app.locales'))) {
        $locale = config('app.fallback_locale');
    }

    session()->put('locale', $locale);
    

    return redirect()->back();
})->name('language');

// http://web_moi_gioi.test/translations
