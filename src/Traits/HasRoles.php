<?php

namespace BoukjijTarik\WooRoleManager\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use BoukjijTarik\WooRoleManager\Models\Role;
use BoukjijTarik\WooRoleManager\Models\Permission;

trait HasRoles
{
    /**
     * Get user roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles()->where('name', $role)->exists();
        }

        return $this->roles()->where('id', $role)->exists();
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole($roles)
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        return $this->roles()->whereIn('name', $roles)->exists();
    }

    /**
     * Check if user has all of the given roles.
     */
    public function hasAllRoles($roles)
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        $userRoleNames = $this->roles()->pluck('name')->toArray();
        
        return count(array_intersect($roles, $userRoleNames)) === count($roles);
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission($permission)
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('name', $permission);
            })
            ->exists();
    }

    /**
     * Check if user has any of the given permissions.
     */
    public function hasAnyPermission($permissions)
    {
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permissions) {
                $query->whereIn('name', $permissions);
            })
            ->exists();
    }

    /**
     * Assign role to user.
     */
    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if ($role) {
            $this->roles()->syncWithoutDetaching([$role->id]);
        }

        return $this;
    }

    /**
     * Remove role from user.
     */
    public function removeRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if ($role) {
            $this->roles()->detach($role->id);
        }

        return $this;
    }

    /**
     * Sync roles for user.
     */
    public function syncRoles($roles)
    {
        if (is_array($roles)) {
            $roleIds = [];
            foreach ($roles as $role) {
                if (is_string($role)) {
                    $roleModel = Role::where('name', $role)->first();
                    if ($roleModel) {
                        $roleIds[] = $roleModel->id;
                    }
                } else {
                    $roleIds[] = $role;
                }
            }
            $this->roles()->sync($roleIds);
        }

        return $this;
    }
} 