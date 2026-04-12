<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class NotificationAttachmentController extends Controller
{
    public function download(Notification $notification, int $index)
    {
        // Verify the user owns this notification
        if (!$notification->applicant || $notification->applicant->user_id !== Auth::id()) {
            Log::warning('Unauthorized download attempt', [
                'user_id' => Auth::id(),
                'notification_id' => $notification->id,
            ]);
            abort(403);
        }

        $attachments = $notification->attachmentItems();

        if (!isset($attachments[$index])) {
            Log::warning('Attachment index not found', [
                'notification_id' => $notification->id,
                'index' => $index,
                'total_attachments' => count($attachments),
            ]);
            abort(404);
        }

        $attachment = $attachments[$index];

        if (empty($attachment['path'])) {
            Log::warning('Attachment has no path', [
                'notification_id' => $notification->id,
                'index' => $index,
                'attachment' => $attachment,
            ]);
            abort(404);
        }

        if (!Storage::disk('local')->exists($attachment['path'])) {
            Log::error('Attachment file not found on disk', [
                'notification_id' => $notification->id,
                'path' => $attachment['path'],
                'disk_root' => Storage::disk('local')->path(''),
            ]);
            abort(404);
        }

        $path = Storage::disk('local')->path($attachment['path']);

        Log::info('File download', [
            'notification_id' => $notification->id,
            'filename' => $attachment['name'],
            'path' => $attachment['path'],
        ]);

        return response()->download($path, $attachment['name']);
    }
}
