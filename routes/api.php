<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\LanguageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;



Route::get('/posts', [PostController::class, 'index'])->name('posts');
Route::get('/posts/slug', [PostController::class, 'generateSlug'])->name('posts.slug.generate');
Route::post('/posts/slug', [PostController::class, 'checkSlug'])->name('posts.slug.check');
Route::get('/companies/check/{companyName?}', [CompanyController::class, 'check'])->name('companies.check');
Route::get('/companies', [CompanyController::class, 'index'])->name('companies');
Route::get('/languages', [LanguageController::class, 'index'])->name('languages');
