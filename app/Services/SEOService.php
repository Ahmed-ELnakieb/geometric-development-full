<?php

namespace App\Services;

use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;

class SEOService
{
    protected $settings;

    public function __construct()
    {
        try {
            $this->settings = \App\Models\SeoSetting::first();
        } catch (\Exception $e) {
            $this->settings = null;
        }
    }

    protected function safeRoute($name, $parameters = [])
    {
        if (app()->runningInConsole()) {
            return config('app.url');
        }
        
        try {
            return route($name, $parameters);
        } catch (\Exception $e) {
            return config('app.url');
        }
    }

    protected function getTitle()
    {
        return $this->settings?->site_title ?? 'Geometric Development - Leading Engineering & Construction Company in Egypt';
    }

    protected function getDescription()
    {
        return $this->settings?->site_description ?? 'Leading engineering and construction company in Egypt';
    }

    protected function getKeywords()
    {
        return $this->settings?->site_keywords ?? ['engineering', 'construction', 'egypt'];
    }
    public function setHomePage()
    {
        SEOMeta::setTitle($this->getTitle())
            ->setDescription($this->getDescription())
            ->setKeywords($this->getKeywords())
            ->setCanonical($this->safeRoute('home'));

        OpenGraph::setTitle('Geometric Development - Engineering Excellence in Egypt')
            ->setDescription('Leading Saudi engineering and construction company delivering innovative architectural design, civil works, and infrastructure projects across Egypt and Emirates.')
            ->setUrl($this->safeRoute('home'))
            ->setType('website')
            ->addImage(asset('assets/img/logo/favicon.png'), [
                'height' => 630,
                'width' => 1200
            ]);

        TwitterCard::setTitle('Geometric Development - Engineering Excellence')
            ->setDescription('Premier engineering and construction solutions in Egypt and Emirates')
            ->setImage(asset('assets/img/logo/favicon.png'));

        JsonLd::setType('Organization')
            ->setName('Geometric Development')
            ->setDescription('Leading Saudi engineering and construction company providing comprehensive solutions in Egypt and Emirates.')
            ->setUrl(config('app.url'))
            ->setLogo(asset('assets/img/logo/favicon.png'))
            ->addValue('address', [
                '@type' => 'PostalAddress',
                'addressCountry' => 'EG',
                'addressRegion' => 'Egypt'
            ])
            ->addValue('contactPoint', [
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
                'availableLanguage' => ['English', 'Arabic']
            ])
            ->addValue('sameAs', [
                'https://www.facebook.com/GeometricDevelopment',
                'https://www.linkedin.com/company/geometric-development',
                'https://twitter.com/GeometricDev'
            ]);
    }

    public function setProjectsPage()
    {
        SEOMeta::setTitle('Our Projects - Geometric Development Engineering Portfolio')
            ->setDescription('Explore Geometric Development\'s portfolio of engineering and construction projects in Egypt and Emirates. From residential developments to commercial infrastructure, discover our innovative solutions.')
            ->setKeywords([
                'geometric development projects',
                'engineering projects egypt',
                'construction portfolio egypt',
                'residential projects egypt',
                'commercial construction egypt',
                'infrastructure projects egypt',
                'ras el hekma development',
                'architectural projects egypt',
                'civil engineering projects'
            ])
            ->setCanonical($this->safeRoute('projects.index'));

        OpenGraph::setTitle('Engineering Projects Portfolio - Geometric Development')
            ->setDescription('Discover our innovative engineering and construction projects across Egypt and Emirates')
            ->setUrl($this->safeRoute('projects.index'))
            ->setType('website');

        TwitterCard::setTitle('Our Engineering Projects Portfolio')
            ->setDescription('Innovative construction and engineering solutions across Egypt and Emirates');
    }

    public function setProjectPage($project)
    {
        $title = $project->title ?? 'Project Details';
        $description = $project->description ?? 'Discover this innovative engineering and construction project by Geometric Development in Egypt.';
        
        SEOMeta::setTitle($title . ' - Geometric Development Project')
            ->setDescription($description)
            ->setKeywords([
                'geometric development',
                $project->title ?? 'project',
                'engineering project egypt',
                'construction project egypt',
                'architectural design',
                'civil engineering',
                'infrastructure development'
            ])
            ->setCanonical($this->safeRoute('projects.show', $project->slug ?? ''));

        OpenGraph::setTitle($title . ' - Engineering Excellence')
            ->setDescription($description)
            ->setUrl($this->safeRoute('projects.show', $project->slug ?? ''))
            ->setType('article');

        if (isset($project->image)) {
            OpenGraph::addImage($project->image);
            TwitterCard::setImage($project->image);
        }

        JsonLd::setType('Project')
            ->setName($title)
            ->setDescription($description)
            ->addValue('creator', [
                '@type' => 'Organization',
                'name' => 'Geometric Development'
            ]);
    }

    public function setBlogPage()
    {
        SEOMeta::setTitle('Engineering Blog - Geometric Development Insights & News')
            ->setDescription('Stay updated with the latest insights, news, and innovations in engineering, construction, and architectural design from Geometric Development experts.')
            ->setKeywords([
                'engineering blog egypt',
                'construction news egypt',
                'architectural design insights',
                'civil engineering articles',
                'infrastructure development news',
                'geometric development blog',
                'construction industry egypt',
                'engineering innovations'
            ])
            ->setCanonical($this->safeRoute('blog.index'));

        OpenGraph::setTitle('Engineering Blog - Industry Insights & News')
            ->setDescription('Latest insights and innovations in engineering and construction from Geometric Development')
            ->setUrl($this->safeRoute('blog.index'))
            ->setType('website');
    }

    public function setBlogPost($post)
    {
        $title = $post->title ?? 'Blog Post';
        $description = $post->excerpt ?? $post->description ?? 'Read the latest insights from Geometric Development engineering experts.';
        
        // Use post's meta_keywords if available, otherwise use defaults
        $keywords = $post->meta_keywords ?? [
            'geometric development',
            'engineering blog',
            'construction insights',
            'architectural design',
            'civil engineering',
            'egypt construction'
        ];
        
        SEOMeta::setTitle($title . ' - Geometric Development Blog')
            ->setDescription($description)
            ->setKeywords($keywords)
            ->setCanonical($this->safeRoute('blog.show', $post->slug ?? ''));

        OpenGraph::setTitle($title)
            ->setDescription($description)
            ->setUrl($this->safeRoute('blog.show', $post->slug ?? ''))
            ->setType('article');

        if (isset($post->image)) {
            OpenGraph::addImage($post->image);
            TwitterCard::setImage($post->image);
        }

        JsonLd::setType('Article')
            ->setName($title)
            ->setDescription($description)
            ->addValue('author', [
                '@type' => 'Organization',
                'name' => 'Geometric Development'
            ])
            ->addValue('publisher', [
                '@type' => 'Organization',
                'name' => 'Geometric Development',
                'logo' => asset('assets/img/logo/favicon.png')
            ]);
    }

    public function setCareersPage()
    {
        SEOMeta::setTitle('Careers - Join Geometric Development Engineering Team')
            ->setDescription('Join Geometric Development\'s team of engineering professionals. Explore career opportunities in construction, architectural design, civil engineering, and project management in Egypt.')
            ->setKeywords([
                'geometric development careers',
                'engineering jobs egypt',
                'construction jobs egypt',
                'architectural design jobs',
                'civil engineering careers',
                'project management jobs egypt',
                'engineering careers egypt',
                'construction company jobs'
            ])
            ->setCanonical($this->safeRoute('careers.index'));

        OpenGraph::setTitle('Engineering Careers - Join Our Team')
            ->setDescription('Explore exciting career opportunities with Geometric Development\'s engineering team')
            ->setUrl($this->safeRoute('careers.index'))
            ->setType('website');
    }

    public function setContactPage()
    {
        SEOMeta::setTitle('Contact Us - Geometric Development Engineering Services')
            ->setDescription('Contact Geometric Development for engineering, construction, and architectural design services in Egypt and Emirates. Get in touch with our expert team for your next project.')
            ->setKeywords([
                'contact geometric development',
                'engineering services egypt',
                'construction services egypt',
                'architectural design services',
                'civil engineering consultation',
                'project management services',
                'engineering company contact egypt'
            ])
            ->setCanonical($this->safeRoute('contact.index'));

        OpenGraph::setTitle('Contact Geometric Development')
            ->setDescription('Get in touch with our engineering and construction experts for your next project')
            ->setUrl($this->safeRoute('contact.index'))
            ->setType('website');

        JsonLd::setType('ContactPage')
            ->setName('Contact Geometric Development')
            ->setDescription('Contact information for Geometric Development engineering and construction services');
    }

    public function setAboutPage()
    {
        SEOMeta::setTitle('About Us - Geometric Development Engineering Company')
            ->setDescription('Learn about Geometric Development, a leading Saudi engineering and construction company specializing in innovative architectural design, civil works, and infrastructure development in Egypt and Emirates.')
            ->setKeywords([
                'about geometric development',
                'engineering company egypt',
                'saudi construction company',
                'architectural design company',
                'civil engineering firm egypt',
                'infrastructure development company',
                'construction management egypt',
                'engineering consultancy egypt'
            ])
            ->setCanonical($this->safeRoute('page.show', 'about'));

        OpenGraph::setTitle('About Geometric Development - Engineering Excellence')
            ->setDescription('Leading Saudi engineering and construction company delivering innovative solutions in Egypt and Emirates')
            ->setUrl($this->safeRoute('page.show', 'about'))
            ->setType('website');
    }

    public function setServicesPage()
    {
        SEOMeta::setTitle('Engineering Services - Geometric Development Solutions')
            ->setDescription('Comprehensive engineering and construction services by Geometric Development including architectural design, civil engineering, infrastructure development, and project management in Egypt and Emirates.')
            ->setKeywords([
                'engineering services egypt',
                'construction services egypt',
                'architectural design services',
                'civil engineering services',
                'infrastructure development services',
                'project management services',
                'building construction egypt',
                'engineering consultancy services',
                'geometric development services'
            ])
            ->setCanonical($this->safeRoute('page.show', 'services'));

        OpenGraph::setTitle('Engineering & Construction Services')
            ->setDescription('Comprehensive engineering solutions including architectural design, civil works, and infrastructure development')
            ->setUrl($this->safeRoute('page.show', 'services'))
            ->setType('website');

        JsonLd::setType('Service')
            ->setName('Engineering & Construction Services')
            ->setDescription('Comprehensive engineering and construction services including architectural design, civil engineering, and infrastructure development')
            ->addValue('provider', [
                '@type' => 'Organization',
                'name' => 'Geometric Development'
            ])
            ->addValue('serviceType', [
                'Architectural Design',
                'Civil Engineering',
                'Infrastructure Development',
                'Project Management',
                'Construction Services'
            ]);
    }

    public function setGenericPage($title, $description, $keywords = [], $canonical = null)
    {
        SEOMeta::setTitle($title . ' - Geometric Development')
            ->setDescription($description)
            ->setKeywords(array_merge([
                'geometric development',
                'engineering egypt',
                'construction egypt'
            ], $keywords));

        if ($canonical) {
            SEOMeta::setCanonical($canonical);
        }

        OpenGraph::setTitle($title)
            ->setDescription($description)
            ->setType('website');

        if ($canonical) {
            OpenGraph::setUrl($canonical);
        }
    }

    public function setBlogCategory($category)
    {
        $categoryName = ucfirst(str_replace('-', ' ', $category));
        
        SEOMeta::setTitle($categoryName . ' Articles - Geometric Development Blog')
            ->setDescription('Read the latest ' . strtolower($categoryName) . ' articles and insights from Geometric Development engineering experts.')
            ->setKeywords([
                'geometric development',
                $categoryName . ' articles',
                'engineering blog egypt',
                'construction insights',
                'architectural design articles'
            ])
            ->setCanonical($this->safeRoute('blog.category', $category));

        OpenGraph::setTitle($categoryName . ' Articles - Engineering Blog')
            ->setDescription('Latest ' . strtolower($categoryName) . ' insights from Geometric Development')
            ->setUrl($this->safeRoute('blog.category', $category))
            ->setType('website');
    }

    public function setBlogTag($tag)
    {
        $tagName = ucfirst(str_replace('-', ' ', $tag));
        
        SEOMeta::setTitle($tagName . ' - Geometric Development Blog')
            ->setDescription('Explore articles tagged with ' . strtolower($tagName) . ' from Geometric Development engineering experts.')
            ->setKeywords([
                'geometric development',
                $tagName,
                'engineering blog egypt',
                'construction articles',
                'architectural insights'
            ])
            ->setCanonical($this->safeRoute('blog.tag', $tag));

        OpenGraph::setTitle($tagName . ' Articles')
            ->setDescription('Articles tagged with ' . strtolower($tagName))
            ->setUrl($this->safeRoute('blog.tag', $tag))
            ->setType('website');
    }

    public function setCareerDetail($career)
    {
        $title = $career->title ?? 'Career Opportunity';
        $description = $career->description ?? 'Join Geometric Development team. Exciting career opportunity in engineering and construction.';
        
        SEOMeta::setTitle($title . ' - Careers at Geometric Development')
            ->setDescription($description)
            ->setKeywords([
                'geometric development careers',
                $title,
                'engineering jobs egypt',
                'construction jobs egypt',
                'architectural design jobs',
                'civil engineering careers'
            ])
            ->setCanonical($this->safeRoute('careers.show', $career->slug ?? ''));

        OpenGraph::setTitle($title . ' - Join Our Team')
            ->setDescription($description)
            ->setUrl($this->safeRoute('careers.show', $career->slug ?? ''))
            ->setType('article');

        JsonLd::setType('JobPosting')
            ->setName($title)
            ->setDescription($description)
            ->addValue('hiringOrganization', [
                '@type' => 'Organization',
                'name' => 'Geometric Development',
                'url' => config('app.url')
            ])
            ->addValue('jobLocation', [
                '@type' => 'Place',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressCountry' => 'EG'
                ]
            ]);
    }
}