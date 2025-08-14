<?php

namespace App\Http\Controllers;

use App\Trait\apiresponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use apiresponse ;
    // عرض الإشعارات
    public function index(Request $request)
    {
        return response()->json([
            'all' => $request->user()->notifications,
            'unread' => $request->user()->unreadNotifications
        ]);
    }

    // تحديد كل الإشعارات كمقروءة
    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return response()->json(['message' => 'All notifications marked as read']);
    }

    // حذف إشعار واحد
    public function destroy(Request $request, $id)
    {
        $notification = $request->user()->notifications()->where('id', $id)->firstOrFail();
        $notification->delete();
        return response()->json(['message' => 'Notification deleted successfully']);
    }
}
