<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:sanctum');
    }

    public function login()
    {
        $user = User::where('email', request('email'))->first();

        if (!$user || !Hash::check(request('password'), $user->password) ) {
            $response = [
                'message' => 'silahkan cek, email dan password salah!',
                'status' => false,
                'data' => (object)[]
            ];
            return response()->json($response, Response::HTTP_UNAUTHORIZED);
        }

        $user->createToken(Str::random(64))->plainTextToken;

        $response = [
            'message' => 'berhasil login',
            'status' => true,
            'data' => UserResource::make($user)
        ];
        return response()->json($response, Response::HTTP_OK);
    }
}
