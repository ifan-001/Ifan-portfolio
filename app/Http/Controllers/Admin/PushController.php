<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PushSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PushController extends Controller
{
    /**
     * Store Web Push notification subscription from phone/browser PWA.
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'endpoint' => 'required|string',
            'public_key' => 'nullable|string',
            'auth_token' => 'nullable|string',
            'device_name' => 'nullable|string',
        ]);

        $sub = PushSubscription::updateOrCreate(
            ['endpoint' => $validated['endpoint']],
            [
                'user_id' => Auth::id(),
                'public_key' => $validated['public_key'] ?? null,
                'auth_token' => $validated['auth_token'] ?? null,
                'device_name' => $validated['device_name'] ?? $request->header('User-Agent'),
            ]
        );

        Log::info('Web Push subscription registered', [
            'id' => $sub->id,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Device subscribed for mobile notifications successfully!',
        ]);
    }

    /**
     * Unsubscribe device.
     */
    public function unsubscribe(Request $request)
    {
        $validated = $request->validate([
            'endpoint' => 'required|string',
        ]);

        PushSubscription::where('endpoint', $validated['endpoint'])->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Subscription removed.',
        ]);
    }

    /**
     * Test notification dispatch.
     */
    public function sendTest(Request $request)
    {
        $count = PushSubscription::count();

        // In a production environment with Minishlink/WebPush and VAPID keys, this dispatches real Web Push payloads.
        // We log and return structured response indicating status.
        Log::info('Push notification test triggered', [
            'admin_id' => Auth::id(),
            'subscriptions_target_count' => $count,
        ]);

        return response()->json([
            'status' => 'success',
            'active_devices' => $count,
            'delivered' => false,
            'message' => $count > 0
                ? "{$count} device(s) registered. Background push delivery is not configured yet (VAPID keys and a push library are required), so no notification was sent."
                : "No registered devices yet. Open the admin app on your phone and tap 'Enable on This Device'.",
        ]);
    }
}
