<?php

namespace App\Http\Controllers;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiAuthController extends Controller
{
    public function authenticate()
    {
        $credentials = request()->only('email', 'password');
        try {
            $token = JWTAuth::attempt($credentials);
            if (!$token) {
                return response()->json(['error' => 'Dados Incorretos'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Houve um erro'], 500);
        }

        return response()->json(['token' => $token], 200);
    }

    public function me()
    {
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);

        return response()->json(['data' => $user], 200);
    }

}