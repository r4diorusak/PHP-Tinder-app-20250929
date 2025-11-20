<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    // GET /api/people
    public function index(Request $request)
    {
        $perPage = 10;
        $people = Person::with('pictures')->paginate($perPage);
        return response()->json($people);
    }

    // POST /api/people/{id}/like
    public function like($id, Request $request)
    {
        $user = $request->user() ?? User::first(); // fallback to first user for testing
        Like::updateOrCreate([
            'user_id' => $user->id,
            'person_id' => $id
        ], ['type' => 'like']);
        return response()->json(['status' => 'liked']);
    }

    // POST /api/people/{id}/dislike
    public function dislike($id, Request $request)
    {
        $user = $request->user() ?? User::first();
        Like::updateOrCreate([
            'user_id' => $user->id,
            'person_id' => $id
        ], ['type' => 'dislike']);
        return response()->json(['status' => 'disliked']);
    }

    // GET /api/likes
    public function likedList(Request $request)
    {
        $user = $request->user() ?? User::first();
        $liked = Like::where('user_id', $user->id)
            ->where('type', 'like')
            ->with('person.pictures')
            ->get()
            ->map(fn($l) => $l->person);
        return response()->json($liked);
    }
}
