<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToWorkshop;
use App\Traits\BelongsToBranch;

class Supplier extends Model
{
    use BelongsToWorkshop, BelongsToBranch;

    protected $fillable = ['name', 'contact_person', 'phone', 'email', 'address', 'workshop_id', 'branch_id'];
}
