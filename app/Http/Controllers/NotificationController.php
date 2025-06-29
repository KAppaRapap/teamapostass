<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = Auth::user()->userNotifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a notification as read.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function markAsRead(Notification $notification)
    {
        // Check if the notification belongs to the user
        if ($notification->user_id !== Auth::id()) {
            return redirect()->route('notifications.index')
                ->with('error', 'Você não tem permissão para acessar esta notificação.');
        }
        
        $notification->markAsRead();
        
        return redirect()->back()
            ->with('success', 'Notificação marcada como lida.');
    }

    /**
     * Mark all notifications as read.
     *
     * @return \Illuminate\Http\Response
     */
    public function markAllAsRead()
    {
        Auth::user()->userNotifications()
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        return redirect()->route('notifications.index')
            ->with('success', 'Todas as notificações foram marcadas como lidas.');
    }

    /**
     * Delete a notification.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        // Check if the notification belongs to the user
        if ($notification->user_id !== Auth::id()) {
            return redirect()->route('notifications.index')
                ->with('error', 'Você não tem permissão para excluir esta notificação.');
        }
        
        $notification->delete();
        
        return redirect()->route('notifications.index')
            ->with('success', 'Notificação excluída com sucesso.');
    }
    
    /**
     * Delete all notifications for the current user.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyAll()
    {
        // Delete all notifications for the current user
        $count = Auth::user()->userNotifications()->count();
        Auth::user()->userNotifications()->delete();
        
        return redirect()->route('notifications.index')
            ->with('success', $count . ' notificações foram eliminadas com sucesso.');
    }

    public function uploadChatFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:5120', // 5MB
        ]);
        $file = $request->file('file');
        $path = $file->store('public/chat_uploads');
        $url = asset(str_replace('public/', 'storage/', $path));
        return response()->json(['url' => $url]);
    }
}
