<?php

use Illuminate\Support\Facades\Route;

Route::get('/ping', static fn () => response()->json(['status' => 'ok']));
