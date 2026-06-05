<?php

namespace App\Traits;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToBranch
{
    protected static function bootBelongsToBranch(): void
    {
        static::creating(function ($model) {
            if (empty($model->branch_id)) {
                if (Auth::check()) {
                    $user = Auth::user();
                    if ($user->isSuperAdmin()) {
                        $model->branch_id = session('active_branch_id');
                    } elseif ($user->isEnterpriseAdmin()) {
                        $model->branch_id = session('active_branch_id');
                    } else {
                        $model->branch_id = $user->branch_id;
                    }
                } else if (session('active_branch_id')) {
                    $model->branch_id = session('active_branch_id');
                }
            }
        });

        static::addGlobalScope('branch', function (Builder $builder) {
            static $resolving = false;
            if ($resolving) {
                return;
            }

            $resolving = true;
            try {
                if (Auth::check()) {
                    $user = Auth::user();
                    $tableName = $builder->getModel()->getTable();
                    
                    if ($user->isSuperAdmin() || $user->isEnterpriseAdmin()) {
                        $activeBranchId = session('active_branch_id');
                        if ($activeBranchId) {
                            $builder->where("{$tableName}.branch_id", $activeBranchId);
                        }
                    } else {
                        if ($user->branch_id) {
                            $builder->where("{$tableName}.branch_id", $user->branch_id);
                        }
                    }
                }
            } finally {
                $resolving = false;
            }
        });
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
