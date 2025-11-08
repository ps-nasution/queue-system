<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QueueService;

class QueueController extends Controller
{
    public function callNext(Request $req, QueueService $svc)
    {
        $validated = $req->validate(['staff_id'=>'required|integer|exists:staff,id']);
        $result = $svc->callNext((int)$validated['staff_id']);

        if (!$result) {
            return response()->noContent(); // 204, tidak ada waiting
        }

        // Jika ada catatan "masih aktif", balas 409
        if (!empty($result['note'] ?? null)) {
            return response()->json($result, 409);
        }

        return $result; // 200 OK
    }
}
