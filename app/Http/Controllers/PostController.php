<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index() {
        $posts = Post::orderBy('id', 'desc')->simplePaginate(5);

        return response(['data' => $posts], 200);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:2|max:255',
            'description' => 'string|min:2|max:255',
            'image' => 'string|min:2|max:255',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 400);
        }

        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $request->image,
        ]);

        return response(['message' => 'Post created successfully.', 'data' => $post], 200);
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'title' => 'string|min:2|max:255',
            'description' => 'nullable|string|min:2|max:255',
            'image' => 'nullable|string|min:2|max:255',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 400);
        }

        $post = Post::find($id);

        if (!$post) {
            return response(['message' => 'Post not found.'], 404);
        }

        $post->update($request->only(['title', 'description', 'image']));

        return response(['message' => 'Post updated successfully.', 'data' => $post], 200);
    }

    public function destroy($id) {
        $post = Post::find($id);

        if (!$post) {
            return response(['message' => 'Post not found.'], 404);
        }

        $post->delete();

        return response(['message' => 'Post deleted successfully.'], 200);
    }
}
