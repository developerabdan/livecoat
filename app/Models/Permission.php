<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasUlids;

    public function permissionGroup()
    {
        return $this->belongsTo(PermissionGroup::class,'permission_group_id');
    }
}
