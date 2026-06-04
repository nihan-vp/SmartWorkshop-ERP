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
        self::create([
            'user_id' => $userId ?? auth()->id(),
            'workshop_id' => $workshopId ?? (auth()->check() ? auth()->user()->workshop_id : null),
            'action' => $action,
            'description' => $description,
        ]);
    }
}
