<?php

namespace App\Services;

use App\Events\UserActivityLogged;
use App\Models\AccountLog;
use App\Models\User;

class AccountLogService
{
    public static function log(User $user, string $activity): AccountLog
    {
        $log = AccountLog::create([
            'user_id'  => $user->id,
            'activity' => $activity,
            'datetime' => now(),
        ]);

        $roleName = $user->roles->first()?->name ?? 'N/A';

        if ($roleName === 'applicant' && $user->applicant) {
            $displayName = trim(
                ($user->applicant->first_name  ?? '') . ' ' .
                ($user->applicant->middle_name ?? '') . ' ' .
                ($user->applicant->last_name   ?? '') . ' ' .
                ($user->applicant->suffix      ?? '')
            );
        } else {
            $displayName = $user->name ?? 'Unknown';
        }

        event(new UserActivityLogged(
            logId:     $log->id,
            userId:    $user->id,
            activity:  $activity,
            datetime:  $log->datetime->toIso8601String(),
            userName:  $displayName,
            userEmail: $user->email ?? '',
            userRole:  $roleName,
        ));

        return $log;
    }
}