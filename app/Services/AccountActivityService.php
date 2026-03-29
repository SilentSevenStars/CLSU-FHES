<?php

namespace App\Services;

use App\Events\UserActivityRecorded;
use App\Models\AccountActivity;
use App\Models\User;

class AccountActivityService
{
    public static function log(User $user, string $activity): AccountActivity
    {
        $record = AccountActivity::create([
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

        event(new UserActivityRecorded(
            activityId: $record->id,
            userId:     $user->id,
            activity:   $activity,
            datetime:   $record->datetime->toIso8601String(),
            userName:   $displayName,
            userEmail:  $user->email ?? '',
            userRole:   $roleName,
        ));

        return $record;
    }
}