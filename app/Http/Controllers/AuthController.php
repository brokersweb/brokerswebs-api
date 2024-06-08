<?php

namespace App\Http\Controllers;

use App\Http\Repositories\SupportRepository;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private SupportRepository $repository;

    public function __construct(SupportRepository $repository)
    {
        $this->repository = $repository;
    }
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            // 'lastname' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'cellphone' => 'required|unique:users,cellphone',
            'phone' => 'unique:users,phone',
            'address' => 'max:255',
            'birthday' => 'date',
            'photo' => 'nullable',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ];
        $valid = $this->validate($request, $rules);
        if ($valid) {
            $fields = $request->all();
            $fields['password'] = Hash::make($request->password);

            // Fullname - extract the full name from the name and lastname fields
            $nameParts = explode(' ', $fields['name']);
            $fields['name'] = $nameParts[0];
            $fields['lastname'] = implode(" ", array_slice($nameParts, 1));

            $user = User::create($fields);
            $user->assignRole('guest');
            $this->repository->sendRegisterUserEmail($request);
            return $this->successResponse($user, Response::HTTP_CREATED);
        } else {
            return $this->errorResponse('Error al registrar el usuario', Response::HTTP_BAD_REQUEST);
        }
    }

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ];
        $this->validate($request, $rules);
        if (!Hash::check($request->password, User::where('email', $request->email)->first()->password)) {
            return $this->errorResponse('El email o contraseña no son correctos', Response::HTTP_BAD_REQUEST);
        }
        $token = Auth::attempt($request->only('email', 'password'));
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => auth()->user()->id,
            'iat' => time(),
            'exp' => time() + 50 * 50,
        ];
        $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'user' => auth()->user()->load('roles'),
            'token' => $token
        ]);
    }

    public function verifyToken(Request $request)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return $this->errorResponse('Token no proporcionado', Response::HTTP_NOT_FOUND);
        }
        try {
            $payload = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            if ($payload->exp >= time()) {
                $user = User::with('roles')->where('id', $payload->sub)->first();
                $payload = [
                    'iss' => "lumen-jwt", // Issuer of the token
                    'sub' => $user->id,
                    'iat' => time(),
                    'exp' => time() + 50 * 50,
                ];
                $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
                return response()->json([
                    'token' => $token,
                    'user' => $user,
                ], Response::HTTP_OK);
            }
            return $this->errorResponse('Token expirado', Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return $this->errorResponse('Token no válido', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
