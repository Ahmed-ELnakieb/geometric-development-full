<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Project;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'user_type' => 'required|in:individual,broker,investor',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Message::create(array_merge($validated, [
            'type' => 'contact',
            'status' => 'new',
            'source_url' => $request->url(),
            'source_page' => 'contact',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]));

        return redirect()->back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }

    public function projectInquiry(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'user_type' => 'required|in:individual,broker,investor',
            'message' => 'required|string',
        ]);

        $project = Project::findOrFail($request->project_id);

        Message::create(array_merge($validated, [
            'type' => 'project_inquiry',
            'status' => 'new',
            'messageable_type' => 'App\\Models\\Project',
            'messageable_id' => $project->id,
            'subject' => "Inquiry about {$project->title}",
            'source_url' => $request->url(),
            'source_page' => 'project_details',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]));

        return redirect()->back()->with('success', 'Your inquiry has been submitted successfully!');
    }
}