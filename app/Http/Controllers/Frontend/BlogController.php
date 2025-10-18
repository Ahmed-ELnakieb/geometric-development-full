<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        // Get featured posts (not paginated)
        $featuredPosts = BlogPost::published()
            ->featured()
            ->recent()
            ->with(['author', 'categories', 'tags', 'featuredImage'])
            ->get();

        // Get regular posts (paginated)
        $posts = BlogPost::published()
            ->where('is_featured', false)
            ->recent()
            ->with(['author', 'categories', 'tags', 'featuredImage'])
            ->paginate(6);

        $categories = BlogCategory::withCount('publishedPosts')
            ->ordered()
            ->get();

        $tags = BlogTag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(10)
            ->get();

        return view('blog.index', compact('posts', 'featuredPosts', 'categories', 'tags'));
    }

    public function category($slug)
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();

        $posts = $category->publishedPosts()
            ->with(['author', 'categories', 'tags', 'featuredImage'])
            ->recent()
            ->paginate(8);

        $categories = BlogCategory::withCount('publishedPosts')
            ->ordered()
            ->get();

        $tags = BlogTag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(10)
            ->get();

        return view('blog.index', compact('posts', 'categories', 'category', 'tags'));
    }

    public function tag($slug)
    {
        $tag = BlogTag::where('slug', $slug)->firstOrFail();

        $posts = $tag->publishedPosts()
            ->with(['author', 'categories', 'tags', 'featuredImage'])
            ->recent()
            ->paginate(8);

        $categories = BlogCategory::withCount('publishedPosts')
            ->ordered()
            ->get();

        $tags = BlogTag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(10)
            ->get();

        return view('blog.index', compact('posts', 'categories', 'tags', 'tag'));
    }

    public function show($slug)
    {
        $post = BlogPost::published()
            ->where('slug', $slug)
            ->with([
                'author',
                'categories',
                'tags',
                'featuredImage',
                'approvedComments' => function ($query) {
                    $query->with('user');
                }
            ])
            ->firstOrFail();

        $relatedPosts = BlogPost::published()
            ->whereHas('categories', function ($q) use ($post) {
                $q->whereIn('blog_categories.id', $post->categories->pluck('id'));
            })
            ->where('id', '!=', $post->id)
            ->with(['author', 'categories', 'tags', 'featuredImage'])
            ->take(3)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
