<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class AuthService
{
    public function __construct(private UserRepository $userRepository) {}

    /**
     * This creates and return the token string
     */
    public function createToken(User $user): array
    {
        $token = $user->createToken(config('app.name').'-Auth-Grant-Client');

        $data = [
            'token' => $token->plainTextToken,
            'type' => 'Bearer',
            'last_used_at' => $token->accessToken->last_used_at?->getTimestamp(),
            'expires_at' => $token->accessToken->expires_at?->getTimestamp(),
        ];

        $user->forceFill(['last_login_at' => now()])->save();

        return $data;
    }

    public function deleteTokens(User $user, bool $deleteAll = false, bool $regenerateNew = false): bool|array
    {
        if ($deleteAll) {
            $user->tokens()?->delete();
        } else {
            return $user->token()?->delete();
        }

        if (true === $regenerateNew) {
            return $this->createToken($user);
        }

        return true;
    }
}
