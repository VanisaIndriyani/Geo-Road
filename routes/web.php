<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoadController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebgisController;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingController::class)->name('home');

Route::get('/webgis', [WebgisController::class, 'public'])->name('webgis.public');
Route::get('/webgis/roads.geojson', [WebgisController::class, 'roadsGeojson'])->name('webgis.roads');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('roads/export/excel', [RoadController::class, 'exportExcel'])->name('roads.export.excel');
        Route::get('roads/export/pdf', [RoadController::class, 'exportPdf'])->name('roads.export.pdf');
        Route::resource('roads', RoadController::class);

        Route::get('webgis', [WebgisController::class, 'admin'])->name('webgis');

        Route::view('statistics', 'admin.statistics')->name('statistics');
        Route::view('reports', 'admin.reports')->name('reports');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
