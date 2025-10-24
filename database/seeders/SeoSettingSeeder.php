<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SeoSetting;

class SeoSettingSeeder extends Seeder
{
    public function run(): void
    {
        // Get all project names to add as keywords
        $projectNames = \App\Models\Project::pluck('title')->toArray();
        
        $keywords = array_merge([
            'geometric development',
            'engineering company egypt',
            'construction company egypt',
            'architectural design egypt',
            'civil engineering egypt',
            'infrastructure development egypt',
            'project management egypt',
            'real estate development egypt',
            'saudi engineering company',
            'ras el hekma projects',
            'residential projects egypt',
            'commercial construction egypt',
            'building contractors egypt',
            'engineering consultancy',
            'construction services',
            'architectural services',
            'civil works egypt',
            'infrastructure projects',
            'construction management',
            'engineering innovation'
        ], $projectNames);

        SeoSetting::create([
            'site_title' => 'Geometric Development - Engineering & Construction Egypt',
            'site_description' => 'Premier Saudi engineering company in Egypt. Architectural design, civil works, infrastructure development & project management across Egypt and Emirates.',
            'site_keywords' => $keywords,
            'facebook_url' => 'https://www.facebook.com/GeometricDevelopment',
            'twitter_handle' => '@GeometricDev',
            'linkedin_url' => 'https://www.linkedin.com/company/geometric-development',
            'instagram_url' => 'https://www.instagram.com/geometricdevelopment',
            'youtube_url' => 'https://www.youtube.com/@geometricdevelopment',
            'google_analytics_id' => '',
            'google_site_verification' => '',
        ]);
    }
}