<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class TagSearchController extends Controller
{
    public function index(Request $request)
    {
        $tag = $request->query('tag');
        $posts = [];
        if ($tag) {
            $posts = Post::where('content', 'LIKE', '%#' . $tag . '%')->latest()->paginate(10);
        }
        return view('livewire.search.tag', compact('tag', 'posts'));
    }
}
