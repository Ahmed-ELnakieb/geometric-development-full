<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Project;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = Page::published()->where('slug', $slug)->with('media')->firstOrFail();

        $data = ['page' => $page];

        // Handle template-specific data
        switch ($page->template) {
            case 'about':
                $data['projects'] = Project::published()->featured()->take(4)->get();
                // Add team members if applicable (assuming a Team model exists, but not specified)
                // $data['teamMembers'] = Team::active()->get(); // Uncomment if Team model is available
                break;
            // Add more cases for other templates as needed, e.g., 'faqs'
            // case 'faqs':
            //     $data['faqs'] = Faq::categorized()->get();
            //     break;
            default:
                // No additional data for default template
                break;
        }

        $view = $page->template ? "pages.{$page->template}" : 'pages.show';

        return view($view, $data);
    }
}