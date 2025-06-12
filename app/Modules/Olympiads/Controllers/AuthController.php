<?php

namespace App\Modules\Olympiads\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController
{
    public function login(Request $request)
    {
        try {
            // Validar que lleguen los campos requeridos y formato correcto
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);
        } catch (ValidationException $e) {
            // Error de validación (campos vacíos, mal formato de email)
            return response()->json([
                'success' => false,
                'message' => 'Datos de acceso inválidos.',
                'errors' => $e->errors(),
            ], 422);
        }

        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'El correo no está registrado.',
            ], 401);
        }

        // Verifica la contraseña manualmente
        if (!\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'La contraseña es incorrecta.',
            ], 401);
        }

        // Autentica y genera el token si todo está bien
        Auth::login($user);
        $token = $user->createToken('user-token')->plainTextToken;
        
        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }
}

