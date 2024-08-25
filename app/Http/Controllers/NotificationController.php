<?php

namespace App\Http\Controllers;
use App\Models\Notification;

use Illuminate\Http\Request;

class NotificationController extends Controller
{   
    public function getNotifications()
{
    try {
        $user_id = auth()->id(); 
        $notifications = Notification::with('post') 
        ->where('id_user', $user_id)
        ->orderBy('id', 'desc')
        ->take(10)
        ->select(['content', 'type', 'date', 'id_post'])
        ->get();

        return response()->json($notifications);
    } catch (\Exception $e) {
        \Log::error('Error fetching notifications: ' . $e->getMessage());
        return response()->json(['error' => 'Internal Server Error: ' . $e->getMessage()], 500);
    }
}
    public function markAsRead($id){
        $notification = Notification::findOrFail($id);
        if($notification->id_user == auth()->id()){
            $notification->markAsRead();
        }
        
    }
    /*
    public function markAllAsRead(){
        auth()->user()->unreadNotifications->markAsRead(); // isto é a mesma coisa que fazer um for loop em todas as notificaçōes
        // https://laravel.com/docs/10.x/notifications #Marking Notifications As Read

    }               Gostava de conseguir usar isto mas acho que precisava de mudar a database porque nao tenho forma de o laravel descobrir
        se a minha notificação esta read or unread.
    */ 

    public function markAllAsRead(){
        auth()->user()->notifications()->delete();
    }
    
}
