<?php
namespace App\Services;

use App\Models\ServiceLog;
use App\Models\Ticket;
use App\Repositories\QueueCounterRepository;
use App\Repositories\TicketRepository;
use Illuminate\Support\Facades\DB;

class QueueService {
    
    public function __construct(
        protected TicketRepository $tickets,
        protected QueueCounterRepository $counters
    ){}

    public function takeTicket(string $type): Ticket {
        return $this->tickets->createToday($type);
    }

    /**
     * Batasi: setiap staff hanya boleh punya 1 tiket aktif (ended_at NULL).
     */
    public function callNext(int $staffId): ?array {
        return DB::transaction(function() use($staffId){
            // 0) Cek apakah staff masih punya tiket aktif
            $activeLog = ServiceLog::where('staff_id', $staffId)
                ->whereNull('ended_at')
                ->lockForUpdate() // cegah double-click race
                ->latest('id')
                ->first();

            if ($activeLog) {
                $t = Ticket::find($activeLog->ticket_id);
                return [
                    'ticket' => $t?->toArray(),
                    'service_log_id' => $activeLog->id,
                    'note' => 'Staff masih memiliki tiket aktif. Selesaikan terlebih dahulu.'
                ];
            }

            // Lock counter harian
            $qc = $this->counters->getToday();
            $qc->refresh();

            $ticket = null;

            // 1) 2R jika tersedia
            if ($qc->r_served_in_cycle < 2) {
                $ticket = $this->tickets->nextWaiting('R');
                if ($ticket) {
                    $qc->r_served_in_cycle++;
                    $qc->save();
                }
            }

            // 2) Jika tidak dapat R, coba W dan reset siklus
            if (!$ticket) {
                $w = $this->tickets->nextWaiting('W');
                if ($w) {
                    $ticket = $w;
                    $qc->r_served_in_cycle = 0;
                    $qc->save();
                }
            }

            // 3) Jika salah satu kosong total, ambil yang ada
            if (!$ticket) {
                $ticket = $this->tickets->nextWaiting('R') ?? $this->tickets->nextWaiting('W');
            }

            if (!$ticket) return null; // tidak ada waiting

            $ticket->status = 'called';
            $ticket->called_at = now();
            $ticket->save();

            $log = ServiceLog::create([
                'staff_id'   => $staffId,
                'ticket_id'  => $ticket->id,
                'started_at' => now(),
            ]);

            return ['ticket' => $ticket->toArray(), 'service_log_id' => $log->id];
        });
    }

    public function markDone(int $ticketId, int $staffId): void {
        DB::transaction(function() use ($ticketId,$staffId){
            $ticket = Ticket::findOrFail($ticketId);
            $ticket->status = 'done';
            $ticket->completed_at = now();
            $ticket->save();

            ServiceLog::where('ticket_id',$ticketId)
                ->where('staff_id',$staffId)
                ->whereNull('ended_at')
                ->latest('id')
                ->limit(1)
                ->update(['ended_at'=>now()]);
        });
    }
}
