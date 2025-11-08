<?php

namespace App\Repositories;

use App\Models\Ticket;

class TicketRepository {

    public function nextWaiting(string $type): ?Ticket
    {    
        return Ticket::where('type',$type)->where('status','waiting')->orderBy('id')->first();
    }

    public function createToday(string $type): Ticket 
    {
        $today = now()->toDateString();
        $count = Ticket::whereDate('created_at',$today)->where('type',$type)->count();
        $num = str_pad($count+1, 3, '0', STR_PAD_LEFT);
        $number = ($type === 'R' ? 'R' : 'W') . $num;
        return Ticket::create(['number'=>$number,'type'=>$type]);
    }

}