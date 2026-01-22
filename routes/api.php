<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/conversations/create', function (Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $userId = Auth::id();
        $targetUserId = $request->user_id;

        // Check if the target user is a follower or being followed
        $isFollower = \App\Models\Follower::where([
            ['follower_id', $userId],
            ['following_id', $targetUserId],
        ])->exists();

        $isFollowing = \App\Models\Follower::where([
            ['follower_id', $targetUserId],
            ['following_id', $userId],
        ])->exists();

        if (!$isFollower && !$isFollowing) {
            return response()->json([
                'success' => false,
                'message' => 'You can only message followers or people you follow.',
            ], 403);
        }

        // Check if conversation already exists
        $existingConversation = Message::where(function($query) use ($userId, $targetUserId) {
            $query->where([
                ['sender_id', $userId],
                ['receiver_id', $targetUserId],
            ])->orWhere([
                ['sender_id', $targetUserId],
                ['receiver_id', $userId],
            ]);
        })->first();

        if ($existingConversation) {
            return response()->json([
                'success' => true,
                'message' => 'Conversation already exists',
                'conversation_id' => $existingConversation->id,
            ]);
        }

        // Create a new conversation by sending an initial message
        $message = Message::create([
            'sender_id' => $userId,
            'receiver_id' => $targetUserId,
            'content' => '👋 Started a conversation',
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Conversation created successfully',
            'conversation_id' => $message->id,
        ]);
    })->name('api.conversations.create');

    // Follow a user
    Route::post('/follow/{userId}', function (Request $request, $userId) {
        $authUser = Auth::user();
        if ($authUser->id == $userId) {
            return response()->json(['success' => false, 'message' => 'You cannot follow yourself.'], 400);
        }
        $targetUser = \App\Models\User::findOrFail($userId);
        $alreadyFollowing = \App\Models\Follower::where('follower_id', $authUser->id)->where('following_id', $targetUser->id)->exists();
        if ($alreadyFollowing) {
            return response()->json(['success' => false, 'message' => 'Already following.'], 400);
        }
        \App\Models\Follower::create([
            'follower_id' => $authUser->id,
            'following_id' => $targetUser->id,
        ]);
        return response()->json(['success' => true, 'message' => 'Followed successfully.']);
    });

    // Unfollow a user
    Route::delete('/follow/{userId}', function (Request $request, $userId) {
        $authUser = Auth::user();
        if ($authUser->id == $userId) {
            return response()->json(['success' => false, 'message' => 'You cannot unfollow yourself.'], 400);
        }
        $targetUser = \App\Models\User::findOrFail($userId);
        $deleted = \App\Models\Follower::where('follower_id', $authUser->id)->where('following_id', $targetUser->id)->delete();
        if ($deleted) {
            return response()->json(['success' => true, 'message' => 'Unfollowed successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'You are not following this user.'], 400);
        }
    });

});
