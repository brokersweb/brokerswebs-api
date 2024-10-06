<?php

namespace App\Traits;

use App\Models\User;
use Spatie\Permission\Exceptions\UnauthorizedException;

trait AuthorizesRoleOrPermission
{
    public function authorizeRoleOrPermission($roleOrPermission)
    {
        /** @var User $user */

        //check logged
        $user = auth()->user();

        if (! $user) {
            throw UnauthorizedException::notLoggedIn();
        }
        //check rule or pemission (like spatie middleware  i.e. "admin|edit posts")
        $rolesOrPermissions = is_array($roleOrPermission)
            ? $roleOrPermission
            : explode('|', $roleOrPermission);

        if (! $user->hasAnyRole($rolesOrPermissions) && ! $user->hasAnyPermission($rolesOrPermissions)) {
            throw UnauthorizedException::forRolesOrPermissions($rolesOrPermissions);
        }
    }
}
