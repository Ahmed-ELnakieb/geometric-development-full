<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\Page;
use App\Services\SEOService;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    protected $seoService;

    public function __construct(SEOService $seoService)
    {
        $this->seoService = $seoService;
    }

    /**
     * Display blog listing page with dynamic content
     */
    public function index()
    {
        $this->seoService->setBlogPage();

        // Load blog page content
        $blogPage = Page::where('slug', 'blog')
            ->orWhere('template', 'blog')
            ->first();

        // Get featured posts (not paginated)
        $featuredPosts = BlogPost::published()
            ->featured()
            ->recent()
            ->with(['author', 'categories', 'tags'])
            ->get();

        // Get regular posts (paginated)
        $posts = BlogPost::published()
            ->where('is_featured', false)
            ->recent()
            ->with(['author', 'categories', 'tags'])
            ->paginate(6);

        $categories = BlogCategory::withCount('publishedPosts')
            ->ordered()
            ->get();

        $tags = BlogTag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(10)
            ->get();

        return view('blog.index', compact('blogPage', 'posts', 'featuredPosts', 'categories', 'tags'));
    }

    public function category($slug)
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();

        $posts = $category->publishedPosts()
            ->with(['author', 'categories', 'tags'])
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
            ->with(['author', 'categories', 'tags'])
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

    /**
     * Display individual blog post detail page with dynamic content
     */
    public function show($slug)
    {
        // Load blog page content for detail page configuration
        $blogPage = Page::where('slug', 'blog')
            ->orWhere('template', 'blog')
            ->first();

        $post = BlogPost::published()
            ->where('slug', $slug)
            ->with([
                'author',
                'categories',
                'tags',
                'approvedComments' => function ($query) {
                    $query->with('user');
                }
            ])
            ->firstOrFail();

        $this->seoService->setBlogPost($post);

        $relatedPosts = BlogPost::published()
            ->whereHas('categories', function ($q) use ($post) {
                $q->whereIn('blog_categories.id', $post->categories->pluck('id'));
            })
            ->where('id', '!=', $post->id)
            ->with(['author', 'categories', 'tags'])
            ->take(3)
            ->get();

        return view('blog.show', compact('blogPage', 'post', 'relatedPosts'));
    }
}
