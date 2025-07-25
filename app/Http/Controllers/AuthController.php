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
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private SupportRepository $repository;

    public function __construct(SupportRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "dni","cellphone", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", maxLength=255, example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="cellphone", type="string", example="1234567890"),
     *             @OA\Property(property="dni", type="string", example="09876543212"),
     *             @OA\Property(property="password", type="string", minLength=8, example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John"),
     *                 @OA\Property(property="lastname", type="string", example="Doe"),
     *                 @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *                 @OA\Property(property="dni", type="string", example="09876543212"),
     *                 @OA\Property(property="cellphone", type="string", example="1234567890"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error al registrar el usuario"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {

        $valid = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'dni' => 'required|min:7',
            'email' => 'required|email|unique:users,email',
            'cellphone' => 'required|unique:users,cellphone',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ]);


        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        try {

            $nameParts = explode(' ', $request->name);

            $user = User::create([
                'name' => $nameParts[0],
                'lastname' => implode(" ", array_slice($nameParts, 1)),
                'email' => $request->email,
                'cellphone' => $request->cellphone,
                'password' =>  Hash::make($request->password),
                'dni' => $request->dni,
                'status' => 'active'
            ]);

            $this->repository->sendRegisterUserEmail($request);

            return $this->successResponseWithMessage('Usuario registrado correctamente, ahora puedes navegar e interactuar en el sistema.', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al registrar el usuario', Response::HTTP_BAD_REQUEST);
        }
    }
    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="User login",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="admin@admin.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="string", format="uuid", example="9bc91c2f-50bb-43d1-b9f7-c81ed7836bef"),
     *                 @OA\Property(property="name", type="string", example="Admin"),
     *                 @OA\Property(property="lastname", type="string", example="Administrator"),
     *                 @OA\Property(property="email", type="string", format="email", example="admin@admin.com"),
     *                 @OA\Property(property="cellphone", type="string", example="1234567890"),
     *                 @OA\Property(property="phone", type="string", nullable=true),
     *                 @OA\Property(property="address", type="string", nullable=true),
     *                 @OA\Property(property="birthday", type="string", format="date", nullable=true),
     *                 @OA\Property(property="photo", type="string", nullable=true),
     *                 @OA\Property(property="status", type="string", example="active"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true),
     *                 @OA\Property(property="roles", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=9),
     *                         @OA\Property(property="name", type="string", example="Administrator"),
     *                         @OA\Property(property="guard_name", type="string", example="api"),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time"),
     *                         @OA\Property(property="pivot", type="object",
     *                             @OA\Property(property="model_type", type="string", example="App\\Models\\User"),
     *                             @OA\Property(property="model_id", type="string", format="uuid"),
     *                             @OA\Property(property="role_id", type="string", format="uuid")
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
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



    public function resetPassword(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'dni' => 'required|exists:users,dni',
            'password' => 'required|min:8',
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        try {

            $user = User::where('dni', $request->dni)->first();

            if (!$user) {
                return $this->errorResponse('El usuario no existe', Response::HTTP_NOT_FOUND);
            }

            $user->update([
                'password' => bcrypt($request->password),
            ]);

            return $this->successResponseWithMessage('Contraseña actualizada con éxito', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al actualizar la contraseña', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
