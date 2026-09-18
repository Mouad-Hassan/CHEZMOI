<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Liste des notifications de l'utilisateur (cahier des charges 9).
     */
    public function index(Request $request): View
    {
        $notifications = $request->user()->notifications()
            ->orderByDesc('date_creation')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Marque toutes les notifications comme lues.
     */
    public function toutMarquerLu(Request $request): RedirectResponse
    {
        $request->user()->notifications()->where('lu', false)->update(['lu' => true]);

        return back()->with('status', 'Toutes les notifications ont été marquées comme lues.');
    }

    /**
     * Marque une notification comme lue.
     */
    public function marquerLu(Request $request, Notification $notification): RedirectResponse
    {
        abort_if($notification->user_id !== $request->user()->id, 403);

        $notification->update(['lu' => true]);

        return back()->with('status', 'Notification marquée comme lue.');
    }
}
