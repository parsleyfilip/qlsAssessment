<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StartPageController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\LabelController;


// de route die naar de start pagina gaat
// roept de controller functie showOrderForm aan die de data van de order doorgeeft aan de view
Route::get('/', [StartPageController::class, 'showOrderForm']);

// route voor process waar ik laat zien wat ik heb gedaan & geleerd (dit is vooral voor mezelf)
Route::get('/process', [ProcessController::class, 'showProcess']);

// route voor het downloaden van de label
Route::get('/download-label/{shipmentId}', [LabelController::class, 'downloadLabel']);

// route die post verzoeken vangt van /create-label (formulier)
// route die get verzoeken vangt van /create-label (formulier)
// daarna wordt labelcontroller uitgevoerd
Route::post('/create-label', [LabelController::class, 'create']);
Route::get('/create-label', function () {
    // redirect naar homepage waar je label download ziet etc
    return redirect('/');
});