<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalog.index');

Route::get('/catalogo/{product:slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/servicios', ServicesController::class)->name('services.index');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
