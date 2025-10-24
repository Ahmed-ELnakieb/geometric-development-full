<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\SEOService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $seoService;

    public function __construct(SEOService $seoService)
    {
        $this->seoService = $seoService;
    }

    public function index(Request $request)
    {
        $this->seoService->setProjectsPage();

        $query = Project::published()->ordered()->with(['categories', 'unitTypes', 'media']);

        if ($request->has('type') && $request->type) {
            $query->type($request->type);
        }

        if ($request->has('status') && $request->status) {
            $query->status($request->status);
        }

        $projects = $query->paginate(12);

        return view('projects.index', compact('projects'));
    }

    public function show($slug)
    {
        $project = Project::published()
            ->where('slug', $slug)
            ->with(['categories', 'unitTypes', 'amenities', 'media'])
            ->firstOrFail();

        $this->seoService->setProjectPage($project);

        $relatedProjects = Project::published()
            ->whereHas('categories', function ($q) use ($project) {
                $q->whereIn('project_categories.id', $project->categories->pluck('id'));
            })
            ->where('id', '!=', $project->id)
            ->take(3)
            ->get();

        return view('projects.show', compact('project', 'relatedProjects'));
    }
}