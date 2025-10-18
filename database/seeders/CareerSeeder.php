<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CareerSeeder extends Seeder
{
    public function run(): void
    {
        // Common benefits for all positions
        $benefits = '<ul><li>Competitive salary</li><li>Health insurance</li><li>Retirement plans</li><li>Team building activities</li><li>Friendly work environment</li><li>Innovation opportunities</li></ul>';

        $careers = [];
        
        $careers[] = [
            'title' => 'Development Manager',
            'slug' => Str::slug('Development Manager'),
            'location' => '6 October - الشيخ زايد',
            'job_type' => 'full_time',
            'salary_range' => 'Competitive',
            'working_days' => 'Sunday – Thursday',
            'overview' => 'Lead the end-to-end development pipeline: land acquisition, feasibility, design coordination, authority approvals, and delivery across coastal and urban projects.',
            'responsibilities' => '<ul><li>Oversee project planning and execution from inception to completion</li><li>Coordinate with cross-functional teams including architects, engineers, and contractors</li><li>Manage stakeholder relationships and communications</li><li>Monitor budget, timelines, and quality standards</li><li>Ensure compliance with local regulations and authority approvals</li></ul>',
            'requirements' => '<ul><li>3+ years of experience in real estate development or related field</li><li>Bachelor\'s degree in Business, Engineering, or related discipline</li><li>Strong project management skills</li><li>Excellent leadership and communication abilities</li><li>Proficiency in relevant software tools</li></ul>',
            'benefits' => $benefits,
            'is_active' => true,
            'is_featured' => true,
            'display_order' => 1,
            'expires_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $careers[] = [
            'title' => 'Project Architect',
            'slug' => Str::slug('Project Architect'),
            'location' => '6 October - الشيخ زايد',
            'job_type' => 'full_time',
            'salary_range' => 'Competitive',
            'working_days' => 'Sunday – Thursday',
            'overview' => 'Deliver concept-to-IFC designs, coordinate with consultants, and align aesthetics, budget, and authority requirements with the brand vision.',
            'responsibilities' => '<ul><li>Develop architectural designs from concept to IFC stage</li><li>Collaborate with consultants and internal teams</li><li>Ensure designs meet aesthetic, budgetary, and regulatory standards</li><li>Prepare and review technical drawings and specifications</li><li>Participate in client presentations and project meetings</li></ul>',
            'requirements' => '<ul><li>3+ years of experience as a project architect</li><li>Degree in Architecture or related field</li><li>Proficiency in AutoCAD, Revit, and other design software</li><li>Knowledge of building codes and regulations</li><li>Strong creative and problem-solving skills</li></ul>',
            'benefits' => $benefits,
            'is_active' => true,
            'is_featured' => false,
            'display_order' => 2,
            'expires_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $careers[] = [
            'title' => 'Site Civil Engineer',
            'slug' => Str::slug('Site Civil Engineer'),
            'location' => '6 October - الشيخ زايد',
            'job_type' => 'full_time',
            'salary_range' => 'Competitive',
            'working_days' => 'Sunday – Thursday',
            'overview' => 'Supervise site works, manage contractors, verify quantities, QA/QC, and ensure compliance with drawings, safety, and timeline.',
            'responsibilities' => '<ul><li>Supervise on-site construction activities</li><li>Manage and coordinate with contractors and subcontractors</li><li>Verify quantities and quality of work</li><li>Conduct QA/QC inspections</li><li>Ensure compliance with safety standards and project timelines</li></ul>',
            'requirements' => '<ul><li>3+ years of experience in civil engineering</li><li>Bachelor\'s degree in Civil Engineering</li><li>Knowledge of construction methods and materials</li><li>Familiarity with safety regulations</li><li>Strong analytical and organizational skills</li></ul>',
            'benefits' => $benefits,
            'is_active' => true,
            'is_featured' => false,
            'display_order' => 3,
            'expires_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $careers[] = [
            'title' => 'Sales Consultant (Real Estate)',
            'slug' => Str::slug('Sales Consultant Real Estate'),
            'location' => '6 October - الشيخ زايد',
            'job_type' => 'full_time',
            'salary_range' => 'Competitive',
            'working_days' => 'Sunday – Thursday',
            'overview' => 'Drive sales for premium residential and mixed‑use projects; manage leads, presentations, site tours, and CRM pipeline.',
            'responsibilities' => '<ul><li>Generate and manage sales leads</li><li>Conduct property presentations and site tours</li><li>Maintain CRM database and sales pipeline</li><li>Meet sales targets and KPIs</li><li>Provide excellent customer service and support</li></ul>',
            'requirements' => '<ul><li>3+ years of experience in real estate sales</li><li>Excellent communication and interpersonal skills</li><li>Knowledge of CRM software</li><li>Ability to work in a fast-paced environment</li><li>Valid driver\'s license and willingness to travel</li></ul>',
            'benefits' => $benefits,
            'is_active' => true,
            'is_featured' => false,
            'display_order' => 4,
            'expires_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $careers[] = [
            'title' => 'Interior Designer',
            'slug' => Str::slug('Interior Designer'),
            'location' => '6 October - الشيخ زايد',
            'job_type' => 'full_time',
            'salary_range' => 'Competitive',
            'working_days' => 'Sunday – Thursday',
            'overview' => 'Develop interior concepts, materials, and FF&E for premium living and hospitality spaces; collaborate with suppliers and site teams.',
            'responsibilities' => '<ul><li>Create interior design concepts for residential and hospitality projects</li><li>Select materials, furniture, and fixtures (FF&E)</li><li>Collaborate with suppliers and vendors</li><li>Coordinate with site teams for implementation</li><li>Prepare design presentations and documentation</li></ul>',
            'requirements' => '<ul><li>3+ years of experience in interior design</li><li>Degree in Interior Design or related field</li><li>Proficiency in design software like SketchUp or AutoCAD</li><li>Knowledge of materials and sustainability practices</li><li>Creative vision and attention to detail</li></ul>',
            'benefits' => $benefits,
            'is_active' => true,
            'is_featured' => false,
            'display_order' => 5,
            'expires_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        
        DB::table('careers')->insert($careers);
    }
}