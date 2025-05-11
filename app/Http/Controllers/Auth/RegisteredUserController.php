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
            //validaton des donnes
            $valiated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'role' => 'in:admin,tutor'
            ]);
    
            //creation de l'utilisateur
            $user = User::create([
                'name' => $valiated['name'],
                'email' => $valiated['email'],
                'password' => Hash::make($valiated['password']),
                //si aucun role n'est choisi il prend tutor par defaut directement 
                'role' => $valiated['role'] ?? 'tutor'
            ]);
    
        
            //cet event à utilser ex lors de la verification de l'email
            event(new Registered($user));
            // Auth::login($user);
    

            //creation d'un token propre à l'utilisateur 
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
