<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        try {
            //cette fonction contient deja la verification des credentials (email et password)
            $request->authenticate();

            //rechercher l'email de l'utilisateur puis comparer
            $user = User::where('email', $request->email)->first();
            
            //creer un token propre à l'utilisateur apres connexion
            $token = $user->createToken('token')->plainTextToken;
    
            return response()->json([
                'data' => [
                    'user' => $request->user(),
                    'token' => $token,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
        

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            //currentAccessToken pour supprimer que le token de la session en cours
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'deconnexion reussie']);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
       
    }
}
