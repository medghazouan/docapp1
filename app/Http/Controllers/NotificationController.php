<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notifications = Notification::where('idDestinataire', Auth::id())
            ->latest()
            ->paginate(10);
        
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        
        if ($notification->idDestinataire != Auth::id()) {
            return redirect()->route('notifications.index')
                ->with('error', 'Vous n\'êtes pas autorisé à marquer cette notification comme lue.');
        }
        
        $notification->update(['read' => true]);
        
        return redirect()->route('notifications.index')
            ->with('success', 'Notification marquée comme lue.');
    }

    public function markAllAsRead()
    {
        Notification::where('idDestinataire', Auth::id())
            ->where('read', false)
            ->update(['read' => true]);
        
        return redirect()->route('notifications.index')
            ->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}
