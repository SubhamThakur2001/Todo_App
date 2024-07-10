<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [TodoController::class, 'index'])->name('tasks.index');
Route::get('/task-list', [TodoController::class, 'ShowAll'])->name('tasks.list');
Route::post('/add-task', [TodoController::class, 'store'])->name('tasks.store');
Route::delete('/tasks/{id}', [TodoController::class, 'destroy'])->name('tasks.destroy');
Route::put('/tasks/completed/{task}', [TodoController::class, 'complete'])->name('tasks.complete');

