<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    /**
     * Create new instance
     */
    public function __construct(private AuthService $authService, private UserRepository $userRepository)
    {
        //
    }

    /**
     * Create a new user account.
     */
    public function register(RegisterRequest $request)
    {
        try {

            DB::beginTransaction();

            $user = $this->userRepository->create($request->only(['name', 'email', 'password']));

            $token = $this->authService->createToken($user);

            DB::commit();

            return api_success('Registration successful.', ['user' => new UserResource($user), 'authentication' => $token], 201);

        } catch (\Throwable $th) {

            DB::rollBack();

            report($th);

            throw $th;
        }
    }

    /**
     * User login action
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->validated()['email'])->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return api_failed('The provided credentials are incorrect.', 401);
        }

        $token = $this->authService->createToken($user);

        return api_success('Logged in successfully.', ['user' => new UserResource($user), 'authentication' => $token]);
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->deleteTokens($request->user());
        return api_success('Log out successful.');
    }
}
