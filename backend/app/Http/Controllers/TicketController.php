<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QueueService;


class TicketController extends Controller
{
    public function store(Request $req, QueueService $svc) 
    {
        $validated = $req->validate(['type'=>'required|in:R,W']);
        $ticket = $svc->takeTicket($validated['type']);
        return response()->json($ticket, 201);
    }

    public function done($id, Request $req, QueueService $svc)
    {
        $validated = $req->validate(['staff_id'=>'required|integer|exists:staff,id']);
        $svc->markDone($id, (int)$validated['staff_id']);
        return ['ok'=>true];
    }
}
