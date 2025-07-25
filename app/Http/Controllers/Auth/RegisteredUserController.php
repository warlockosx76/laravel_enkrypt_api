<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Http;

use function Laravel\Prompts\password;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
{
    $request->validate([
        'username' => 'required|string|unique:users,username',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|confirmed|min:6',
    ]);

    $salt = Str::random(16);
    $user = User::create([
        'username' => $request->username,
        'email' => $request->email,
        'salt' => $salt,
        'password' => bcrypt($salt . $request->password),
    ]);

    event(new Registered($user));

    $token = $user->createToken('auth_token')->plainTextToken;

    Http::withHeaders(['Authorization' => 'EX9RF8AS9QGOPdEM',])->post('http://localhost:9090/plugins/restapi/v1/users', 
    [
        'username' => $user->id,
        'password' => $request->password,
        'name' => $user->username,
        'email' => $user->email,
    ]);

    return response()->json([
        'token' => $token,
        'user' => $user,
    ]);
}
}
