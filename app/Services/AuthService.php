<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Register a new user.
     *
     * This method creates a new user using the provided data,
     * then generates an authentication token for immediate login.
     *
     * @param array $data
     * @return array [$user, $token]
     */
    public function register(array $data)
    {
        $user = User::create($data);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [$user, $token];
    }

    /**
     * Authenticate user and generate token.
     *
     * This method checks if the provided email exists and verifies
     * the password using hashing. If authentication fails, it throws
     * a validation exception. On success, it returns the user and token.
     *
     * @param array $credentials
     * @return array [$user, $token]
     * @throws ValidationException
     */
    public function login(array $credentials)
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['wrong data'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [$user, $token];
    }
}