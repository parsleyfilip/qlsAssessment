<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StartPageController;
use App\Http\Controllers\ProcessController;


// de route die naar de start pagina gaat
// roept de controller functie showOrderForm aan die de data van de order doorgeeft aan de view
Route::get('/', [StartPageController::class, 'showOrderForm']);

Route::get('/process', [ProcessController::class, 'showProcess']);