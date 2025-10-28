<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    use HasUlids;
    protected $table = "permission_groups";
    protected $fillable = [
        'category',
        "name",
        "slug",
        "icon",
        "description"
    ];
    public function permissions()
    {
        return $this->hasMany(Permission::class,'permission_group_id');
    }
}
