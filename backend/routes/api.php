<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TicketController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\DashboardController;
use App\Models\Staff;
use App\Models\QueueCounter;
use App\Models\ServiceLog;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

Route::post('/tickets', [TicketController::class, 'store']);
Route::post('/queue/call-next', [QueueController::class, 'callNext']);
Route::patch('/tickets/{id}/done', [TicketController::class, 'done']);
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/display/current', [DashboardController::class, 'displayCurrent']);

Route::get('/staff', fn()=> Staff::all());
Route::patch('/staff/{id}/active', function($id, Request $r){
    $s = Staff::findOrFail($id);
    $s->active = $r->boolean('active', true);
    $s->save();
    return $s;
});

Route::get('/truncate', function(){
    DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

    QueueCounter::truncate();
    ServiceLog::truncate();
    Ticket::truncate();

    DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    return ['ok'=>true];
});
