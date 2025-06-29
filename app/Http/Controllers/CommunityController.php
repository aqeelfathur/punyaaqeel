<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    // Tampilkan halaman komunitas
    public function index()
    {
        $posts = Post::with(['user', 'comments.user', 'likes'])->latest()->get();
        $user = auth()->user();
        
        return view('community', compact('posts', 'user'));
    }

    // Simpan post baru
    public function storePost(Request $request)
    {
        try {
            // Debug: Log request data
            \Log::info('Store Post Request:', [
                'isi' => $request->isi,
                'user_id' => Auth::id(),
                'user_id_nama' => Auth::user()->id_nama ?? 'null',
                'all_request' => $request->all(),
                'headers' => $request->headers->all()
            ]);

            $request->validate([
                'isi' => 'required|string|max:5000',
            ]);

            // Check if user is authenticated
            if (!Auth::check()) {
                \Log::error('User not authenticated');
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            // Check if user has id_nama
            $user = Auth::user();
            if (!$user->id_nama) {
                \Log::error('User id_nama is null', ['user_id' => $user->id]);
                return response()->json(['error' => 'User id_nama is required'], 400);
            }

            $post = Post::create([
                'id_nama' => $user->id_nama,
                'isi' => $request->isi,
            ]);

            \Log::info('Post created successfully:', [
                'post_id' => $post->id,
                'id_nama' => $post->id_nama,
                'isi' => $post->isi
            ]);

            // Return JSON response untuk AJAX
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Post created successfully.',
                    'post' => $post->load(['user', 'comments.user', 'likes'])
                ], 201);
            }

            return redirect()->back()->with('success', 'Post created successfully.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation Error:', $e->errors());
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json(['error' => 'Validation failed', 'messages' => $e->errors()], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Store Post Error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json(['error' => 'Internal server error: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    // Tambahkan komentar - FIXED
    public function storeComment(Request $request, $postId)
    {
        try {
            \Log::info('Store Comment Request:', [
                'post_id' => $postId,
                'isi_komentar' => $request->isi_komentar,
                'user_id_nama' => Auth::user()->id_nama ?? 'null'
            ]);

            $request->validate([
                'isi_komentar' => 'required|string|max:1000',
            ]);

            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            // Check if post exists
            $post = Post::find($postId);
            if (!$post) {
                return response()->json(['error' => 'Post not found'], 404);
            }

            // Check if user has id_nama
            $user = Auth::user();
            if (!$user->id_nama) {
                return response()->json(['error' => 'User id_nama is required'], 400);
            }

            $comment = Comment::create([
                'post_id' => $postId,
                'id_nama' => $user->id_nama,
                'isi_komentar' => $request->isi_komentar,
            ]);

            \Log::info('Comment created successfully:', ['comment_id' => $comment->id]);

            // Return JSON response untuk AJAX
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Comment added successfully',
                    'comment' => $comment->load('user')
                ]);
            }

            return redirect()->back()->with('success', 'Comment added successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Comment Validation Error:', $e->errors());
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json(['error' => 'Validation failed', 'messages' => $e->errors()], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Store Comment Error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'post_id' => $postId
            ]);
            
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json(['error' => 'Failed to add comment: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Failed to add comment');
        }
    }

    // Toggle like/unlike - FIXED
    public function toggleLike($postId)
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            // Check if post exists
            $post = Post::find($postId);
            if (!$post) {
                return response()->json(['error' => 'Post not found'], 404);
            }

            $user = Auth::user();
            if (!$user->id_nama) {
                return response()->json(['error' => 'User id_nama is required'], 400);
            }

            $userId = $user->id_nama;
            $like = Like::where('post_id', $postId)->where('id_nama', $userId)->first();

            if ($like) {
                $like->delete();
                $liked = false;
                \Log::info('Like removed:', ['post_id' => $postId, 'user_id' => $userId]);
            } else {
                Like::create([
                    'post_id' => $postId,
                    'id_nama' => $userId,
                ]);
                $liked = true;
                \Log::info('Like added:', ['post_id' => $postId, 'user_id' => $userId]);
            }

            // Get updated like count
            $likeCount = Like::where('post_id', $postId)->count();

            return response()->json([
                'success' => true,
                'liked' => $liked,
                'count' => $likeCount
            ]);
        } catch (\Exception $e) {
            \Log::error('Toggle Like Error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'post_id' => $postId
            ]);
            
            return response()->json([
                'error' => 'Failed to toggle like: ' . $e->getMessage()
            ], 500);
        }
    }
}