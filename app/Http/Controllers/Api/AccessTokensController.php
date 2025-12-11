<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Laravel\Sanctum\PersonalAccessToken;

class AccessTokensController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
            'device_name' => 'string|max:255',
            'abilities' => 'nullable|array',
        ]);
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $device_name = $request->device_name ?: $request->userAgent();
            $token = $user->createToken($device_name, $request->abilities); // public function createToken(string $name, array $abilities = ['*'])
            return Response::json([
                'code' => 1,
                'token' => $token->plainTextToken,
                'user' => $user
            ], 201); // 201 => Created | Success | A new resource was made. | "Success + New Item"
        }
        return Response::json([
            'code' => 0,
            'message' => 'Invalid Credentials'
        ], 401); // 401 => Unauthorized | Client Error | Missing or invalid login. | "Who are you?"
    }











    public function destroy($token = null)
    {
        $user = Auth::guard('sanctum')->user();
        // $user->tokens()->where('token', $token)->delete();  // tokens is a relationship here | that code line cant work cause the token were hashed not plain

        if (null === $token) {
            // $user->currentAccessToken()->delete();
            // OR:
            $currentToken = $user->currentAccessToken();
            if ($currentToken) {
                $user->tokens()->where('id', $currentToken->id)->delete();
            }
            return Response::json([
                'code' => 1,
                'message' => 'Token deleted'
            ], 204); // 204 => No Content | Success | A resource was deleted. | "Success + No Content"
        }
        $personalAccessToken = PersonalAccessToken::findToken($token);  // PersonalAccessToken is a model of Sanctum // findToken() return that model
        if ($user->id === $personalAccessToken->tokenable_id && get_class($user) === $personalAccessToken->tokenable_type) {
            $personalAccessToken->delete();
        }
        return Response::json([
            'code' => 1,
            'message' => 'Token deleted'
        ], 200);

        // revoke all tokens for the user From all Devices for example
        // $user->tokens()->delete();
    }
}
