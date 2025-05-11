<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Picture;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements = Announcement::where('is_canceled', false)
            ->where('is_completed', false)
            ->get();

        return response()->json(['data' => $announcements]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'operation_type' => 'required|in:buy,don,exchange',
                'state' => 'required|in:neuf,comme_neuf,bon_etat,usage,abime',
                'price' => 'nullable|numeric|min:0',
                'exchange_location_address' => 'required|string',
                'exchange_location_longt' => 'required|numeric',
                'exchange_location_lat' => 'required|numeric',
                'pictures.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            ]);

            $user = auth()->user();

            if (!$user || $user->role !== 'tutor') {
                return response()->json(['error' => 'Seuls les tuteurs peuvent créer des annonces.'], 403);
            }

            $announcement = Announcement::create([
                'user_id' => $user->id,
                'category_id' => $validated['category_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'operation_type' => $validated['operation_type'],
                'state' => $validated['state'],
                'price' => $request->input('price'),
                'exchange_location_address' => $validated['exchange_location_address'],
                'exchange_location_longt' => $validated['exchange_location_longt'],
                'exchange_location_lat' => $validated['exchange_location_lat'],
            ]);

            // upload multiple des images
            if ($request->hasFile('pictures')) {
                foreach ($request->file('pictures') as $image) {
                    $path = $image->store('announcements', 'public');

                    Picture::create([
                        'announcement_id' => $announcement->id,
                        'url' => $path,
                    ]);
                }
            }

            $announcement->load(['category', 'user', 'pictures']);

            return response()->json([
                'data' => $announcement
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la création de l\'annonce.',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Announcement $announcement)
    {
        return response()->json(['data' => $announcement]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        try {
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'title' => 'required|string',
                'description' => 'required|string',
                'operation_type' => 'required',
                'state' => 'required|string',
                'exchange_location_address' => 'required|string',
                'exchange_location_longt' => 'required|numeric',
                'exchange_location_lat' => 'required|numeric',
            ]);

            $user = auth()->user();

            if ($announcement->user_id !== $user->id) {
                return response()->json(['error' => 'Not authorized to update']);
            }

            $announcement->update([
                'user_id' => $user->id,
                'category_id' => $validated['category_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'operation_type' => $validated['operation_type'],
                'state' => $validated['state'],
                'price' => $request->input('price'),
                'exchange_location_address' => $validated['exchange_location_address'],
                'exchange_location_longt' => $validated['exchange_location_longt'],
                'exchange_location_lat' => $validated['exchange_location_lat'],
            ]);
            return response()->json([
                'data' => [
                    'annonce' => $announcement
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        $user = auth()->user();

        if ($announcement->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['error' => 'Not authorized to delete'], 403);
        }

        $announcement->delete();
        return response()->json(['message' => 'Announcement deleted ']);
    }

    //cette function recupère que les anonces créée par l'utilisateur
    public function myAnnoucements(Announcement $announcement){
        $user = auth()->user();
        $announcement = $user->announcements;
        $announcement->load('user', 'category', 'pictures');
        return response()->json(['data' => $announcement]);
    }
}
