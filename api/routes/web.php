<?php

declare(strict_types = 1);

use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\View\Factory;

Route::get('/', fn(): Factory|\Illuminate\Contracts\View\View => view('welcome'));
