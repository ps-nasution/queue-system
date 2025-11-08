<?php

namespace App\Repositories;

use App\Models\QueueCounter;

class QueueCounterRepository {

    public function getToday(): ?QueueCounter
    {    
        return QueueCounter::firstOrCreate(['service_date' => now()->toDateString()]); 
    }

    public function incrementR(QueueCounter $qc): void 
    {
        $qc->r_served_in_cycle = min(2, $qc->r_served_in_cycle + 1);
        $qc->save();
    }

    public function resetCycle(QueueCounter $qc): void 
    {
        $qc->r_served_in_cycle = 0;
        $qc->save();
    }

}