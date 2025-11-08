<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueCounter extends Model
{
    protected $fillable = ['service_date','r_served_in_cycle'];
}
