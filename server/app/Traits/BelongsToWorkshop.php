<?php

namespace App\Traits;

use App\Models\Workshop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToWorkshop
{
    protected static function bootBelongsToWorkshop(): void
    {
        static::creating(function ($model) {
            if (empty($model->workshop_id)) {
                if (Auth::check()) {
                    $user = Auth::user();
                    if ($user->isSuperAdmin()) {
                        $model->workshop_id = session('active_workshop_id');
                    } else {
                        $model->workshop_id = $user->workshop_id;
                    }
                } else if (session('active_workshop_id')) {
                    // Fallback for session active workshop (e.g. dynamic actions)
                    $model->workshop_id = session('active_workshop_id');
                }
            }
        });

        static::addGlobalScope('workshop', function (Builder $builder) {
            static $resolving = false;
            if ($resolving) {
                return;
            }

            $resolving = true;
            try {
                if (Auth::check()) {
                    $user = Auth::user();
                    if ($user->isSuperAdmin()) {
                        $activeWorkshopId = session('active_workshop_id');
                        if ($activeWorkshopId) {
                            $tableName = $builder->getModel()->getTable();
                            $builder->where("{$tableName}.workshop_id", $activeWorkshopId);
                        }
                    } else {
                        $tableName = $builder->getModel()->getTable();
                        $builder->where("{$tableName}.workshop_id", $user->workshop_id);
                    }
                }
            } finally {
                $resolving = false;
            }
        });
    }

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }
}
