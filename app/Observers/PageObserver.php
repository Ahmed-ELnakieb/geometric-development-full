<?php

namespace App\Observers;

use App\Models\Page;

class PageObserver
{
    /**
     * System pages that should have locked slugs
     */
    protected $systemPages = [
        'home' => 'home',
        'about' => 'about',
        'contact' => 'contact',
        'projects' => 'projects',
        'careers' => 'careers',
        'blog' => 'blog',
    ];

    /**
     * Handle the Page "saving" event.
     * This runs before the model is saved to the database.
     */
    public function saving(Page $page): void
    {
        // Check if this is a system page by template
        if (isset($this->systemPages[$page->template])) {
            // Force the slug to match the template
            $page->slug = $this->systemPages[$page->template];
        }
    }

    /**
     * Handle the Page "updating" event.
     */
    public function updating(Page $page): void
    {
        // Check if this is a system page by template
        if (isset($this->systemPages[$page->template])) {
            // Force the slug to match the template
            $page->slug = $this->systemPages[$page->template];
        }
    }
}
