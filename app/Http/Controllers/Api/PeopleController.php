<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="PHP Tinder app 20250929",
 *     version="1.0.0",
 *     description="Dating application API with swipe cards, likes/dislikes, and match functionality. Developed by Khairul Adha",
 *     @OA\Contact(
 *         email="r4dioz.88@gmail.com",
 *         name="Khairul Adha"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://127.0.0.1:8000",
 *     description="Local Development Server"
 * )
 * 
 * @OA\Server(
 *     url="http://localhost",
 *     description="XAMPP Server"
 * )
 */
class PeopleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/people",
     *     summary="Get recommended people",
     *     description="Get paginated list of recommended people excluding already liked/disliked ones",
     *     operationId="getPeople",
     *     tags={"People"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="Current user ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of results per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=10, example=10)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", default=1, example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Sarah Johnson"),
     *                     @OA\Property(property="age", type="integer", example=25),
     *                     @OA\Property(property="pictures", type="array", @OA\Items(type="string", example="https://i.pravatar.cc/300?img=1")),
     *                     @OA\Property(property="location", type="string", example="New York, USA"),
     *                     @OA\Property(property="bio", type="string", example="Love hiking, coffee, and adventure!"),
     *                     @OA\Property(property="gender", type="string", enum={"male", "female", "other"}, example="female")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="pagination",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=29),
     *                 @OA\Property(property="last_page", type="integer", example=3),
     *                 @OA\Property(property="has_more", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        // Get the current user ID from request (in real app, use auth)
        $currentUserId = $request->input('user_id', 1);
        
        // Get pagination parameters
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        // Get the current user
        $currentUser = Person::findOrFail($currentUserId);

        // Get IDs of people already liked or disliked
        $likedIds = $currentUser->liked()->pluck('liked_id')->toArray();
        $dislikedIds = $currentUser->disliked()->pluck('disliked_id')->toArray();
        $excludedIds = array_merge($likedIds, $dislikedIds, [$currentUserId]);

        // Get recommended people (not yet interacted with)
        $people = Person::whereNotIn('id', $excludedIds)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $people->items(),
            'pagination' => [
                'current_page' => $people->currentPage(),
                'per_page' => $people->perPage(),
                'total' => $people->total(),
                'last_page' => $people->lastPage(),
                'has_more' => $people->hasMorePages(),
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/people/{id}/like",
     *     summary="Like a person",
     *     description="Like a person and detect if it's a match (mutual like)",
     *     operationId="likePerson",
     *     tags={"People"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Person ID to like",
     *         required=true,
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id"},
     *             @OA\Property(property="user_id", type="integer", example=1, description="Current user ID")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully liked",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Person liked successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="match", type="boolean", example=true, description="True if both users liked each other"),
     *                 @OA\Property(property="person_name", type="string", example="Sarah Johnson")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Person not found"),
     *     @OA\Response(response=400, description="Already liked this person")
     * )
     */
    public function like(Request $request, int $id): JsonResponse
    {
        // Get the current user ID from request (in real app, use auth)
        $currentUserId = $request->input('user_id', 1);
        $simulateMatch = $request->input('simulate_match', false);

        // Validate that the person exists
        $person = Person::findOrFail($id);
        $currentUser = Person::findOrFail($currentUserId);

        // Check if already liked
        if ($currentUser->hasLiked($id)) {
            return response()->json([
                'success' => false,
                'message' => 'You have already liked this person'
            ], 400);
        }

        // Check if already disliked, remove dislike first
        if ($currentUser->hasDisliked($id)) {
            $currentUser->disliked()->detach($id);
        }

        // Add like
        $currentUser->liked()->attach($id);

        // Check for match (mutual like)
        $isMatch = $person->hasLiked($currentUserId);
        
        // If simulate_match is true, make the person like back (for testing/demo)
        if ($simulateMatch && !$isMatch) {
            // Make the person like back to create a match
            if (!$person->hasLiked($currentUserId)) {
                $person->liked()->attach($currentUserId);
                $isMatch = true;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Person liked successfully',
            'is_match' => $isMatch,
            'data' => [
                'liked_person' => $person
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/people/{id}/dislike",
     *     summary="Dislike a person",
     *     description="Pass/reject a person",
     *     operationId="dislikePerson",
     *     tags={"People"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Person ID to dislike",
     *         required=true,
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id"},
     *             @OA\Property(property="user_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully disliked",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Person disliked successfully")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Person not found"),
     *     @OA\Response(response=400, description="Already disliked this person")
     * )
     */
    public function dislike(Request $request, int $id): JsonResponse
    {
        // Get the current user ID from request (in real app, use auth)
        $currentUserId = $request->input('user_id', 1);

        // Validate that the person exists
        $person = Person::findOrFail($id);
        $currentUser = Person::findOrFail($currentUserId);

        // Check if already disliked
        if ($currentUser->hasDisliked($id)) {
            return response()->json([
                'success' => false,
                'message' => 'You have already disliked this person'
            ], 400);
        }

        // Check if already liked, remove like first
        if ($currentUser->hasLiked($id)) {
            $currentUser->liked()->detach($id);
        }

        // Add dislike
        $currentUser->disliked()->attach($id);

        return response()->json([
            'success' => true,
            'message' => 'Person disliked successfully'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/people/liked",
     *     summary="Get liked people list",
     *     description="Get paginated list of people that the current user has liked",
     *     operationId="getLikedPeople",
     *     tags={"People"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="Current user ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of results per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=10, example=10)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", default=1, example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="Sarah Johnson"),
     *                     @OA\Property(property="age", type="integer", example=25),
     *                     @OA\Property(property="pictures", type="array", @OA\Items(type="string", example="https://i.pravatar.cc/300?img=1")),
     *                     @OA\Property(property="location", type="string", example="New York, USA"),
     *                     @OA\Property(property="bio", type="string", example="Love hiking, coffee, and adventure!"),
     *                     @OA\Property(property="gender", type="string", enum={"male", "female", "other"}, example="female")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="pagination",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=15),
     *                 @OA\Property(property="last_page", type="integer", example=2),
     *                 @OA\Property(property="has_more", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="User not found")
     * )
     * 
     * Get list of liked people.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function likedList(Request $request): JsonResponse
    {
        // Get the current user ID from request (in real app, use auth)
        $currentUserId = $request->input('user_id', 1);
        
        // Get pagination parameters
        $perPage = $request->input('per_page', 10);

        // Get the current user
        $currentUser = Person::findOrFail($currentUserId);

        // Get liked people with pagination
        $likedPeople = $currentUser->liked()
            ->orderBy('likes.created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $likedPeople->items(),
            'pagination' => [
                'current_page' => $likedPeople->currentPage(),
                'per_page' => $likedPeople->perPage(),
                'total' => $likedPeople->total(),
                'last_page' => $likedPeople->lastPage(),
                'has_more' => $likedPeople->hasMorePages(),
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/people/disliked",
     *     summary="Get disliked people list",
     *     description="Get list of people that the user has disliked/passed on",
     *     operationId="getDislikedList",
     *     tags={"People"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="Current user ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="Jane Smith"),
     *                     @OA\Property(property="age", type="integer", example=25),
     *                     @OA\Property(property="pictures", type="array", @OA\Items(type="string", example="https://i.pravatar.cc/300?img=2")),
     *                     @OA\Property(property="location", type="string", example="New York, USA"),
     *                     @OA\Property(property="bio", type="string", example="Coffee addict and book lover"),
     *                     @OA\Property(property="gender", type="string", enum={"male", "female", "other"}, example="female")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="pagination",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=25),
     *                 @OA\Property(property="last_page", type="integer", example=3),
     *                 @OA\Property(property="has_more", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="User not found")
     * )
     * 
     * Get list of people that the user has disliked.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function dislikedList(Request $request): JsonResponse
    {
        // Get the current user ID from request (in real app, use auth)
        $currentUserId = $request->input('user_id', 1);
        
        // Get pagination parameters
        $perPage = $request->input('per_page', 10);

        // Get the current user
        $currentUser = Person::findOrFail($currentUserId);

        // Get disliked people with pagination
        $dislikedPeople = $currentUser->disliked()
            ->orderBy('dislikes.created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $dislikedPeople->items(),
            'pagination' => [
                'current_page' => $dislikedPeople->currentPage(),
                'per_page' => $dislikedPeople->perPage(),
                'total' => $dislikedPeople->total(),
                'last_page' => $dislikedPeople->lastPage(),
                'has_more' => $dislikedPeople->hasMorePages(),
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/people/matches",
     *     summary="Get matches list",
     *     description="Get list of mutual likes (matches) - people who liked each other",
     *     operationId="getMatches",
     *     tags={"People"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="Current user ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=3),
     *                     @OA\Property(property="name", type="string", example="Emily Davis"),
     *                     @OA\Property(property="age", type="integer", example=26),
     *                     @OA\Property(property="pictures", type="array", @OA\Items(type="string", example="https://i.pravatar.cc/300?img=3")),
     *                     @OA\Property(property="location", type="string", example="Los Angeles, USA"),
     *                     @OA\Property(property="bio", type="string", example="Beach lover and yoga enthusiast"),
     *                     @OA\Property(property="gender", type="string", enum={"male", "female", "other"}, example="female")
     *                 )
     *             ),
     *             @OA\Property(property="count", type="integer", example=5, description="Total number of matches")
     *         )
     *     ),
     *     @OA\Response(response=404, description="User not found")
     * )
     * 
     * Get list of matches (mutual likes).
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function matches(Request $request): JsonResponse
    {
        // Get the current user ID from request (in real app, use auth)
        $currentUserId = $request->input('user_id', 1);
        
        // Get the current user
        $currentUser = Person::findOrFail($currentUserId);

        // Get people who have liked back (mutual likes)
        $matches = $currentUser->liked()
            ->whereHas('liked', function ($query) use ($currentUserId) {
                $query->where('liked_id', $currentUserId);
            })
            ->orderBy('likes.created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $matches,
            'count' => $matches->count()
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/people/liked-opponents",
     *     summary="Get liked opponents list",
     *     description="Get list of people who have liked the current user (opponents who liked you)",
     *     operationId="getLikedOpponents",
     *     tags={"People"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="Current user ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=5),
     *                     @OA\Property(property="name", type="string", example="Michael Brown"),
     *                     @OA\Property(property="age", type="integer", example=28),
     *                     @OA\Property(property="pictures", type="array", @OA\Items(type="string", example="https://i.pravatar.cc/300?img=5")),
     *                     @OA\Property(property="location", type="string", example="Chicago, USA"),
     *                     @OA\Property(property="bio", type="string", example="Sports enthusiast and travel lover"),
     *                     @OA\Property(property="gender", type="string", enum={"male", "female", "other"}, example="male"),
     *                     @OA\Property(property="is_match", type="boolean", example=false, description="True if you also liked them back")
     *                 )
     *             ),
     *             @OA\Property(property="count", type="integer", example=8, description="Total number of people who liked you")
     *         )
     *     ),
     *     @OA\Response(response=404, description="User not found")
     * )
     * 
     * Get list of people who have liked the current user.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function likedOpponents(Request $request): JsonResponse
    {
        // Get the current user ID from request (in real app, use auth)
        $currentUserId = $request->input('user_id', 1);
        
        // Get the current user
        $currentUser = Person::findOrFail($currentUserId);

        // Get people who have liked the current user
        $opponents = Person::whereHas('liked', function ($query) use ($currentUserId) {
            $query->where('liked_id', $currentUserId);
        })->orderBy('created_at', 'desc')->get();

        // Add is_match flag to each opponent
        $opponentsWithMatchStatus = $opponents->map(function ($opponent) use ($currentUser) {
            $opponent->is_match = $currentUser->hasLiked($opponent->id);
            return $opponent;
        });

        return response()->json([
            'success' => true,
            'data' => $opponentsWithMatchStatus,
            'count' => $opponentsWithMatchStatus->count()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/people/check-popular",
     *     summary="Check and notify popular people",
     *     description="Manually trigger checking for people with 50+ likes and send email notification to admin",
     *     operationId="checkPopularPeople",
     *     tags={"Admin"},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully checked and sent notifications",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Found 2 popular people and sent notifications"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="count", type="integer", example=2),
     *                 @OA\Property(
     *                     property="people",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=81),
     *                         @OA\Property(property="name", type="string", example="Test Popular Person"),
     *                         @OA\Property(property="likes_count", type="integer", example=51)
     *                     )
     *                 ),
     *                 @OA\Property(property="admin_email", type="string", example="admin@example.com")
     *             )
     *         )
     *     )
     * )
     */
    public function checkPopularPeople(): JsonResponse
    {
        // Find people with 50 or more likes
        $popularPeople = Person::has('likedBy', '>=', 50)
            ->withCount('likedBy')
            ->get();

        $adminEmail = env('ADMIN_EMAIL', 'admin@example.com');
        $notifications = [];

        foreach ($popularPeople as $person) {
            // Send email notification
            \Illuminate\Support\Facades\Mail::raw(
                "🔥 Popular Person Alert!\n\n" .
                "Person: {$person->name}\n" .
                "ID: {$person->id}\n" .
                "Total Likes: {$person->liked_by_count}\n" .
                "Location: {$person->location}\n" .
                "Age: {$person->age}\n\n" .
                "This person has reached {$person->liked_by_count} likes!",
                function ($message) use ($adminEmail, $person) {
                    $message->to($adminEmail)
                        ->subject("🔥 Popular Person Alert: {$person->name} has {$person->liked_by_count} likes!");
                }
            );

            $notifications[] = [
                'id' => $person->id,
                'name' => $person->name,
                'likes_count' => $person->liked_by_count
            ];
        }

        return response()->json([
            'success' => true,
            'message' => "Found {$popularPeople->count()} popular people and sent notifications",
            'data' => [
                'count' => $popularPeople->count(),
                'people' => $notifications,
                'admin_email' => $adminEmail
            ]
        ]);
    }
}

