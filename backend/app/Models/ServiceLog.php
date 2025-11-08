<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceLog extends Model
{
    protected $fillable = ['staff_id','ticket_id','started_at','ended_at'];
    
    public function staff(){ 
        return $this->belongsTo(Staff::class); 
    }

    public function ticket(){ 
        return $this->belongsTo(Ticket::class); 
    }
    
}
