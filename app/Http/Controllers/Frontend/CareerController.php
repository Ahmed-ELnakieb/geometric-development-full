<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\CareerApplication;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index(Request $request)
    {
        $query = Career::active()->notExpired()->ordered();

        if ($request->has('location') && $request->location) {
            $query->where('location', $request->location);
        }

        if ($request->has('job_type') && $request->job_type) {
            $query->where('job_type', $request->job_type);
        }

        $careers = $query->paginate(10);

        $locations = Career::active()->notExpired()->distinct()->pluck('location');
        $jobTypes = Career::active()->notExpired()->distinct()->pluck('job_type');

        return view('careers.index', compact('careers', 'locations', 'jobTypes'));
    }

    public function show($slug)
    {
        $career = Career::active()->notExpired()->where('slug', $slug)->firstOrFail();

        $relatedCareers = Career::active()->notExpired()
            ->where('location', $career->location)
            ->where('id', '!=', $career->id)
            ->take(3)
            ->get();

        return view('careers.show', compact('career', 'relatedCareers'));
    }

    public function apply($slug, Request $request)
    {
        $career = Career::active()->notExpired()->where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'portfolio_url' => 'nullable|url|max:255',
        ]);

        $careerApplication = CareerApplication::create([
            'career_id' => $career->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'portfolio_url' => $validated['portfolio_url'] ?? null,
            'status' => 'new',
            'source_url' => $request->url(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($request->hasFile('cv_file')) {
            $media = $careerApplication->addMediaFromRequest('cv_file')->toMediaCollection('cv_files');
            $careerApplication->update(['cv_file_id' => $media->id]);
        }

        return redirect()->back()->with('success', 'Your application has been submitted successfully!');
    }
}