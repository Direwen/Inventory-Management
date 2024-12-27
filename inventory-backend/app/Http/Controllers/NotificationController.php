<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateNotificationRequest;
use App\Models\Notification;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    use ApiResponseTrait;
    public function index()
    {
        $notis = Notification::where("user_id", auth()->id())
            ->where('is_read', false)  // Only unread notifications
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->successResponse(
            data: $notis,
            message: "Fetched unread notifications"
        );
    }

    public function update(UpdateNotificationRequest $request)
    {
        $ids = $request->validated()['notification_ids'];

        Notification::whereIn('id', $ids)
            ->where('user_id', auth()->id())  // Ensure user can only update their own notifications
            ->update(['is_read' => true]);

        return $this->successResponse(
            message: "Notifications marked as read"
        );
    }


}
