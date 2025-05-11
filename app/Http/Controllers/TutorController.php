<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use App\Models\User;
use Illuminate\Http\Request;

class TutorController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                "telephone" => "required|string",
                "profession" => "required|string"
            ]);

            $user = auth()->user();

            if (!$user || $user->role !== "tutor") {
                return response()->json(['error' => 'Cet utilisateur n\'est pas tuteur'], 403);
            }

            $tutor = Tutor::updateOrCreate(
                ["user_id" => $user->id],
                [
                    "telephone" => $validate["telephone"],
                    "profession" => $validate["profession"]
                ]
            );

            return response()->json([
                'data' => [
                    'tutor' => $tutor
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
