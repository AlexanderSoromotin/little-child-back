<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|min:2|max:255',
            'last_name' => 'required|string|min:2|max:255',
            'patronymic' => 'nullable|string|min:2|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|max:255',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 400);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'patronymic' => $request->patronymic,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response(["message" => "User registered successfully.", "data" => $user], 200);
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $token = Auth::user()->createToken('Little-Child-Back');

            return response(["message" => "User logged in successfully.", "data" => [
                "access_token" => $token->plainTextToken,
            ]], 200);

        } else {
            return response(['message' => 'Invalid auth credentials.'], 400);
        }
    }

    public function user()
    {
        $user = Auth::user();
        $user->load("roles");
        $user->roles->makeHidden(['pivot', 'created_at', 'updated_at']);

        return response(['data' => $user], 200);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->token()->revoke();

        return response(['message' => 'User logged out successfully.'], 200);
    }

    public function closeSessions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 400);
        }

        $user = User::find($request->user_id);
        $user->tokens()->delete();

        return response(['message' => 'User sessions deleted successfully.'], 200);
    }

    public function checkEmailAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 400);
        }

        $user = User::where('email', $request->email)->get()->first();

        if ($user) {
            return response(['message' => 'Email is unavailable.'], 200);
        }

        return response(['message' => 'Email is available.'], 200);
    }

    public function checkAccessToken(Request $request) {
        // Получение access токена из запроса

        $request->headers->set('Authorization', 'Bearer ' . $request->input('access_token'));

        try {
            // Проверка аутентификации пользователя
            $user = Auth::guard('api')->user();

            if ($user) {
                // Действия при валидном токене
                return response()->json(['message' => 'Token is valid.', 'data' => ['user_id' => $user->id]]);
            } else {
                // Действия при невалидном токене
                return response()->json(['message' => 'Token is invalid.'], 401);
            }
        } catch (\Exception $e) {
            // Обработка исключения (например, если токен не был предоставлен)
            return response()->json(['message' => 'Token is invalid.'], 401);
        }
    }
}
