<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtRoleAdminMiddleware
{

    public function handle($request, Closure $next, $guard = null)
    {
        $auth = $request->header('Authorization');

        if ($auth === '') {
            return response()->json(['error' => 'Token not provided'], 401);
        }
        $auth = explode(' ', $auth);
        if ($auth[0] !== 'Bearer') {
            return null;
        }
        $token = $auth[1];
        $credentials = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
        $role_name = User::find($credentials->sub)->roles()->first()->name ?? '';
        if ($role_name == 'Administrator') {
            return $next($request);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
