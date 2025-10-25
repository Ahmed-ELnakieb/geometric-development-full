<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\SEOService;
use Symfony\Component\HttpFoundation\Response;

class SEOMiddleware
{
    protected $seoService;

    public function __construct(SEOService $seoService)
    {
        $this->seoService = $seoService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Skip SEO processing during console commands
            if (app()->runningInConsole()) {
                return $next($request);
            }

            // Skip SEO for admin, API, and other non-frontend routes
            if ($request->is('admin/*') || 
                $request->is('filament/*') || 
                $request->is('api/*') || 
                $request->is('pwa-test') ||
                $request->is('notifications/*') ||
                $request->is('manifest.json') ||
                $request->is('sw.js')) {
                return $next($request);
            }

            // Set SEO based on route
            $routeName = $request->route() ? $request->route()->getName() : null;
            
            if (!$routeName) {
                return $next($request);
            }
        
        switch ($routeName) {
            case 'home':
                $this->seoService->setHomePage();
                break;
            case 'projects.index':
                $this->seoService->setProjectsPage();
                break;
            case 'projects.show':
                $project = $request->route('slug');
                $this->seoService->setProjectPage((object)['slug' => $project, 'title' => ucfirst(str_replace('-', ' ', $project))]);
                break;
            case 'blog.index':
                $this->seoService->setBlogPage();
                break;
            case 'blog.show':
                $post = $request->route('slug');
                $this->seoService->setBlogPost((object)['slug' => $post, 'title' => ucfirst(str_replace('-', ' ', $post))]);
                break;
            case 'blog.category':
                $category = $request->route('slug');
                $this->seoService->setBlogCategory($category);
                break;
            case 'blog.tag':
                $tag = $request->route('slug');
                $this->seoService->setBlogTag($tag);
                break;
            case 'careers.show':
                $career = $request->route('slug');
                $this->seoService->setCareerDetail((object)['slug' => $career, 'title' => ucfirst(str_replace('-', ' ', $career))]);
                break;
            case 'careers.index':
                $this->seoService->setCareersPage();
                break;
            case 'contact.index':
                $this->seoService->setContactPage();
                break;
            case 'page.show':
                $slug = $request->route('slug');
                if ($slug === 'about') {
                    $this->seoService->setAboutPage();
                } elseif ($slug === 'services') {
                    $this->seoService->setServicesPage();
                } else {
                    $this->seoService->setGenericPage(
                        ucfirst(str_replace('-', ' ', $slug)),
                        'Learn more about ' . str_replace('-', ' ', $slug) . ' at Geometric Development.',
                        [],
                        $request->url()
                    );
                }
                break;
            default:
                // Set default SEO for any other routes
                $this->seoService->setGenericPage(
                    'Geometric Development',
                    'Leading engineering and construction company in Egypt.',
                    [],
                    $request->url()
                );
                break;
        }
        } catch (\Exception $e) {
            // Log error but don't crash the application
            \Log::error('SEO Middleware Error: ' . $e->getMessage(), [
                'route' => $request->route() ? $request->route()->getName() : 'unknown',
                'url' => $request->url(),
                'trace' => $e->getTraceAsString()
            ]);
            // Continue without SEO if there's an error
        }

        return $next($request);
    }
}