<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'workshop_id',
        'action',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    public static function log($action, $description, $userId = null, $workshopId = null)
    {
        $resolvedUserId = $userId ?? auth()->id();
        if ($resolvedUserId && !\App\Models\User::where('id', $resolvedUserId)->exists()) {
            $resolvedUserId = null;
        }

        $resolvedWorkshopId = $workshopId ?? (auth()->check() ? auth()->user()->workshop_id : null);
        if (!$resolvedWorkshopId && session()->has('active_workshop_id')) {
            $resolvedWorkshopId = session('active_workshop_id');
        }
        if ($resolvedWorkshopId && !\App\Models\Workshop::where('id', $resolvedWorkshopId)->exists()) {
            $resolvedWorkshopId = null;
        }

        self::create([
            'user_id' => $resolvedUserId,
            'workshop_id' => $resolvedWorkshopId,
            'action' => $action,
            'description' => $description,
        ]);
    }
}
