<?php

namespace BoukjijTarik\WooRoleManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'guard_name'
    ];

    /**
     * Get roles that have this permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'permission_role', 'permission_id', 'role_id');
    }

    /**
     * Get users that have this permission through their roles.
     */
    public function users()
    {
        return $this->roles()->with('users')->get()->pluck('users')->flatten()->unique('id');
    }
} 