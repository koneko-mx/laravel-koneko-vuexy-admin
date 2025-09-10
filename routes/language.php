<?php

use Illuminate\Support\Facades\Route;
use Koneko\KonekoVuexyAdmin\Application\Http\Controllers\LanguageController;

Route::get('lang/{locale}', [LanguageController::class, 'swap'])->name('language.swap');
