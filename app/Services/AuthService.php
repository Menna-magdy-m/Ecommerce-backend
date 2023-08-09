<?php

namespace App\Services;

use App\Models\User;
use Hash;

class AuthService extends Service
{
    /**
     * login
     */


    public function login(array $credentials)
    {

        $user = User::query()->where('user_email', '=', $credentials['user_email'])->get();
        if (isset($user[0])) {
            $user = $user[0];

            if ($user instanceof User && $user->user_status == User::status_active) {
                //$credentials["password"] = $credentials["user_pass"];

                if (Hash::check($credentials['user_pass'], $user->user_pass)) {
                    //
                        $token = $user->createToken('*');

                        $data = [
                            'user' => $user->toLightWeightArray(),
                            'token' => $token->plainTextToken,
                        ];

                        return $this->ok($data, 'auth:login:succeed');
                    } else       throw new \Exception('auth:login:errors:credentials:not allowed');

                }
        }
        throw new \Exception('auth:login:errors:credentials');
    }

    public function me()
    {
        $user = auth()->user();

        if ($user instanceof User) {
            return $this->ok($user->toLightWeightArray(), 'auth:me:done');
        }
        throw new \Exception('auth:me:errors:unauthenticated');

    }

    public function logout()
    {
        $user = auth()->user();
        if ($user instanceof User) {
            $user->tokens()->delete();
            return $this->ok(true, 'auth:logout:done');
        }

        throw new \Exception('auth:logout:errors:unauthenticated');
    }
}
