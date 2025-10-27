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
        try {
            $this->seoService->setProjectsPage();

            // Get the projects page data
            $page = \App\Models\Page::where('slug', 'projects')->first();

            if (! $page) {
                \Log::error('Projects page not found in database');
                abort(404, 'Projects page not found');
            }

            return view('pages.projects', compact('page'));
        } catch (\Exception $e) {
            \Log::error('ProjectController@index Error: '.$e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            abort(500, 'Error loading projects page');
        }
    }

    public function show($slug)
    {
        try {
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
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::warning('Project not found: '.$slug);
            abort(404, 'Project not found');
        } catch (\Exception $e) {
            \Log::error('ProjectController@show Error: '.$e->getMessage(), [
                'slug' => $slug,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            abort(500, 'Error loading project');
        }
    }
}
