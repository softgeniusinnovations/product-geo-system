<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use App\Jobs\PersistLikeJob;


class LikeController extends Controller
{
    public function toggle(Request $request)
    {
        try {
            $user = Auth::user();
            if (! $user) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            $entityType = $request->input('entity_type');
            $entityId = (int)$request->input('entity_id');
            $action = (int)$request->input('action'); // 1 for like, -1 for unlike

            if (! in_array($action, [1, -1], true)) {
                return response()->json(['error' => 'Invalid action'], 400);
            }

            $counterKey = $this->counterKey($entityType, $entityId);

            Redis::throttle('likes_throttle')->allow(10)->every(1)->then(function () use ($counterKey, $action) {
                Redis::incrby($counterKey, $action);
            }, function () {
            });

            // queue persistence
            PersistLikeJob::dispatch([
                'user_id' => $user->id,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'action' => $action,
            ]);

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            Log::error("LikeController::toggle error: " . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    protected function counterKey(string $type, int $id): string
    {
        return "like_counter:" . md5($type) . ":{$id}";
    }
}
