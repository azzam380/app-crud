<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\MemberController;

Route::get('/members', [MemberController::class, 'index'])->name('members.index');
Route::post('/members/create', [MemberController::class, 'create'])->name('members.create');
Route::post('/members/update', [MemberController::class, 'update'])->name('members.update');
Route::post('/members/delete', [MemberController::class, 'delete'])->name('members.delete');