<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;    
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                //'type_user' => 'required|integer|in:1,2,3', // 1: Admin, 2: Instructor, 3: Student
                'phone' => 'required|string|max:20',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'type_user' => 3,
                'phone' => $request->phone,
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['message' => 'Usuario registrado exitosamente', 'user' => $user, 'token' => $token], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Registro fallido', 'message' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Intenta nuevamente.'], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['message' => 'Inicio de sesión exitoso', 'user' => $user, 'token' => $token], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error de inicio de sesión', 'message' => $e->getMessage()], 500);
        }
    }
}
