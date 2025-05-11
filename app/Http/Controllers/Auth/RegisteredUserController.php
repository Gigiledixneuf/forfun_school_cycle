<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $valiated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'role' => 'in:admin,tutor'
            ]);
    
            $user = User::create([
                'name' => $valiated['name'],
                'email' => $valiated['email'],
                'password' => Hash::make($valiated['password']),
                'role' => $valiated['role'] ?? 'tutor'
            ]);
    
        
            event(new Registered($user));
            // Auth::login($user);
    

            $token = $user->createToken('token')->plainTextToken;
            return response()->json([
                "data" => [
                    'token' => $token,
                    'user' => $user
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage() 
            ]);
        }
        
    }
}
