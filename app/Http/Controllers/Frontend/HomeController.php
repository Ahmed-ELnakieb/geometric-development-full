<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Career;
use App\Models\Project;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $projects = Project::published()
            ->featured()
            ->ordered()
            ->take(6)
            ->with('categories')
            ->get()
            ->each(function ($project) {
                $project->loadMedia('gallery');
            });

        $blogPosts = BlogPost::published()
            ->recent()
            ->take(3)
            ->with('author', 'categories')
            ->get();

        $careers = Career::active()
            ->notExpired()
            ->featured()
            ->take(3)
            ->get();

        return view('home', compact('projects', 'blogPosts', 'careers'));
    }
}