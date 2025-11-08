<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Staff;
use App\Models\ServiceLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $waiting = Ticket::where('status','waiting')->count();
        $activeStaff = Staff::where('active',true)->count();
        $top = ServiceLog::select('staff_id', DB::raw('count(*) as total'))
                ->groupBy('staff_id')->orderByDesc('total')->with('staff')->limit(3)->get()
                ->map(fn($r)=>[
                'staff_id'=>$r->staff_id,
                'name'=>$r->staff->name,
                'total'=>$r->total
        ]);

        $avg = ServiceLog::select('staff_id', DB::raw('avg(timestampdiff(SECOND, 
                started_at, ended_at)) as avg_seconds'))
                ->whereNotNull('ended_at')
                ->groupBy('staff_id')->with('staff')->get()
                ->map(fn($r)=>[
                'staff_id'=>$r->staff_id,
                'name'=>$r->staff->name,
                'avg_seconds'=> (int)$r->avg_seconds
        ]);

        return compact('waiting','activeStaff','top','avg');
    }

    public function displayCurrent()
    {
        $called = Ticket::where('status','called')->latest('called_at')->take(5)->get();
        return $called;
    }
}
