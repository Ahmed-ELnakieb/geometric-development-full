<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectUnitType;
use App\Models\ProjectAmenity;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProjectSeeder extends Seeder
{
    /**
     * Helper function to ensure image exists by copying from geo-design if available
     */
    private function ensureImageExists(string $relativePath): string
    {
        $publicPath = public_path($relativePath);
        $geoDesignPath = base_path("geo-design/" . $relativePath);

        // If the public file doesn't exist, try to copy from geo-design
        if (!file_exists($publicPath) && file_exists($geoDesignPath)) {
            $publicDir = dirname($publicPath);
            if (!is_dir($publicDir)) {
                mkdir($publicDir, 0755, true);
            }
            copy($geoDesignPath, $publicPath);
        }

        return $publicPath;
    }

    /**
     * Add media to project with proper collections
     */
    private function addProjectMedia(Project $project, array $images = []): void
    {
        echo "Adding media for project: {$project->title} (ID: {$project->id})\n";

        // Default image sets if not provided
        if (empty($images)) {
            $images = [
                "hero_slider" => [
                    "assets/img/random/random (10).png",
                    "assets/img/random/random (11).png",
                    "assets/img/random/random (12).png",
                    "assets/img/random/random (13).png",
                ],
                "hero_thumbnails" => [
                    "assets/img/random/random (10).png",
                    "assets/img/random/random (11).png",
                    "assets/img/random/random (12).png",
                    "assets/img/random/random (13).png",
                ],
                "about_image" => "assets/img/random/random (14).png",
                "gallery" => [
                    "assets/img/random/random (15).png",
                    "assets/img/random/random (16).png",
                    "assets/img/random/random (17).png",
                    "assets/img/random/random (18).png",
                    "assets/img/random/random (19).png",
                    "assets/img/random/random (20).png",
                ],
            ];
        }

        // Add hero slider images
        if (isset($images["hero_slider"]) && is_array($images["hero_slider"])) {
            echo "  Adding " .
                count($images["hero_slider"]) .
                " hero_slider images...\n";
            foreach ($images["hero_slider"] as $imagePath) {
                $fullPath = $this->ensureImageExists($imagePath);
                echo "    Checking: {$imagePath} -> {$fullPath}\n";
                if (file_exists($fullPath)) {
                    echo "    File exists, adding to hero_slider collection\n";
                    try {
                        $project
                            ->addMedia($fullPath)
                            ->preservingOriginal()
                            ->toMediaCollection("hero_slider");
                        echo "    Successfully added!\n";
                    } catch (\Exception $e) {
                        echo "    ERROR: " . $e->getMessage() . "\n";
                    }
                } else {
                    echo "    File does not exist: {$fullPath}\n";
                }
            }
        }

        // Add hero thumbnails
        if (
            isset($images["hero_thumbnails"]) &&
            is_array($images["hero_thumbnails"])
        ) {
            echo "  Adding " .
                count($images["hero_thumbnails"]) .
                " hero_thumbnails images...\n";
            foreach ($images["hero_thumbnails"] as $imagePath) {
                $fullPath = $this->ensureImageExists($imagePath);
                echo "    Checking: {$imagePath} -> {$fullPath}\n";
                if (file_exists($fullPath)) {
                    echo "    File exists, adding to hero_thumbnails collection\n";
                    try {
                        $project
                            ->addMedia($fullPath)
                            ->preservingOriginal()
                            ->toMediaCollection("hero_thumbnails");
                        echo "    Successfully added!\n";
                    } catch (\Exception $e) {
                        echo "    ERROR: " . $e->getMessage() . "\n";
                    }
                } else {
                    echo "    File does not exist: {$fullPath}\n";
                }
            }
        }

        // Add about image
        if (
            isset($images["about_image"]) &&
            is_string($images["about_image"])
        ) {
            echo "  Adding about_image...\n";
            $fullPath = $this->ensureImageExists($images["about_image"]);
            echo "    Checking: {$images["about_image"]} -> {$fullPath}\n";
            if (file_exists($fullPath)) {
                echo "    File exists, adding to about_image collection\n";
                try {
                    $project
                        ->addMedia($fullPath)
                        ->preservingOriginal()
                        ->toMediaCollection("about_image");
                    echo "    Successfully added!\n";
                } catch (\Exception $e) {
                    echo "    ERROR: " . $e->getMessage() . "\n";
                }
            } else {
                echo "    File does not exist: {$fullPath}\n";
            }
        }

        // Add gallery images
        if (isset($images["gallery"]) && is_array($images["gallery"])) {
            echo "  Adding " .
                count($images["gallery"]) .
                " gallery images...\n";
            foreach ($images["gallery"] as $imagePath) {
                $fullPath = $this->ensureImageExists($imagePath);
                echo "    Checking: {$imagePath} -> {$fullPath}\n";
                if (file_exists($fullPath)) {
                    echo "    File exists, adding to gallery collection\n";
                    try {
                        $project
                            ->addMedia($fullPath)
                            ->preservingOriginal()
                            ->toMediaCollection("gallery");
                        echo "    Successfully added!\n";
                    } catch (\Exception $e) {
                        echo "    ERROR: " . $e->getMessage() . "\n";
                    }
                } else {
                    echo "    File does not exist: {$fullPath}\n";
                }
            }
        }

        echo "  Media addition complete for {$project->title}\n\n";
    }

    public function run(): void
    {
        // Retrieve categories by slug
        $residential = ProjectCategory::where("slug", "residential")->first();
        $commercial = ProjectCategory::where("slug", "commercial")->first();
        $mixedUse = ProjectCategory::where("slug", "mixed-use")->first();
        $investment = ProjectCategory::where("slug", "investment")->first();

        // Project 1: MUROJ
        $muroj = Project::create([
            "title" => "MUROJ",
            "slug" => "muroj",
            "location" => "Ras Al Khaimah, UAE",
            "type" => "villa",
            "status" => "in_progress",
            "excerpt" =>
                "A premium beachfront residential community in Ras Al Khaimah offering luxury villas and apartments with stunning sea views.",
            "description" =>
                '<h2>Project Overview</h2><p>MUROJ represents a pinnacle of luxury living in Ras Al Khaimah, UAE. This exclusive beachfront development offers residents an unparalleled lifestyle, combining modern architecture with the serenity of coastal living. Positioned in Mina\'s North Harbour, MUROJ provides easy access to Dubai and Abu Dhabi while maintaining the tranquility of a secluded paradise.</p><h3>Location Advantages</h3><p>The project\'s prime location offers proximity to world-class amenities, including international schools, healthcare facilities, and shopping centers. With direct access to major highways and the upcoming Al Marjan Island developments, residents enjoy seamless connectivity to the UAE\'s business hubs.</p><h3>Architectural Design</h3><p>MUROJ features resort-inspired residences with panoramic sea views. The architectural design blends traditional Arabian elements with contemporary aesthetics, creating a harmonious balance between heritage and modernity. Each unit is meticulously crafted to maximize natural light and ventilation.</p><ul><li>Premium finishes and materials</li><li>Smart home technology integration</li><li>Sustainable building practices</li><li>Private balconies and terraces</li></ul><h3>Amenities and Lifestyle</h3><p>The community offers world-class amenities designed to enhance everyday living. From infinity pools overlooking the Gulf to wellness centers and recreational facilities, MUROJ provides everything residents need for a luxurious lifestyle.</p><h3>Investment Potential</h3><p>With Ras Al Khaimah\'s growing tourism and real estate sectors, MUROJ presents excellent investment opportunities. The project\'s strategic location and high-quality development make it an attractive option for both residential and rental investments.</p>',
            "property_size_min" => 1200,
            "property_size_max" => 4500,
            "total_units" => 250,
            "completion_date" => Carbon::parse("2026-12-31"),
            "is_published" => true,
            "is_featured" => true,
            "display_order" => 1,
        ]);
        $muroj->categories()->attach([$residential->id, $investment->id]);

        // Unit Types for MUROJ
        ProjectUnitType::create([
            "project_id" => $muroj->id,
            "name" => "Studio Apartment",
            "description" =>
                "Compact and efficient studio with modern finishes",
            "size_min" => 45,
            "size_max" => 60,
            "display_order" => 1,
        ]);
        ProjectUnitType::create([
            "project_id" => $muroj->id,
            "name" => "1-Bedroom Apartment",
            "description" => "Spacious one-bedroom unit with balcony",
            "size_min" => 70,
            "size_max" => 90,
            "display_order" => 2,
        ]);
        ProjectUnitType::create([
            "project_id" => $muroj->id,
            "name" => "2-Bedroom Apartment",
            "description" => "Comfortable two-bedroom apartment with sea views",
            "size_min" => 110,
            "size_max" => 140,
            "display_order" => 3,
        ]);
        ProjectUnitType::create([
            "project_id" => $muroj->id,
            "name" => "3-Bedroom Apartment",
            "description" =>
                "Luxurious three-bedroom unit with premium amenities",
            "size_min" => 160,
            "size_max" => 200,
            "display_order" => 4,
        ]);
        ProjectUnitType::create([
            "project_id" => $muroj->id,
            "name" => "Penthouse",
            "description" => "Exclusive penthouse with panoramic views",
            "size_min" => 250,
            "size_max" => 350,
            "display_order" => 5,
        ]);

        // Amenities for MUROJ
        ProjectAmenity::create([
            "project_id" => $muroj->id,
            "title" => "Infinity Pool",
            "description" => "Stunning infinity pool overlooking the sea",
            "icon" => "fa-swimming-pool",
            "display_order" => 1,
        ]);
        ProjectAmenity::create([
            "project_id" => $muroj->id,
            "title" => "Fitness Center",
            "description" => "State-of-the-art gym with modern equipment",
            "icon" => "fa-dumbbell",
            "display_order" => 2,
        ]);
        ProjectAmenity::create([
            "project_id" => $muroj->id,
            "title" => 'Children\'s Play Area',
            "description" => "Safe and fun play area for kids",
            "icon" => "fa-child",
            "display_order" => 3,
        ]);
        ProjectAmenity::create([
            "project_id" => $muroj->id,
            "title" => "Landscaped Gardens",
            "description" =>
                "Beautifully maintained gardens throughout the community",
            "icon" => "fa-leaf",
            "display_order" => 4,
        ]);
        ProjectAmenity::create([
            "project_id" => $muroj->id,
            "title" => "24/7 Security",
            "description" => "Round-the-clock security for peace of mind",
            "icon" => "fa-shield-alt",
            "display_order" => 5,
        ]);
        ProjectAmenity::create([
            "project_id" => $muroj->id,
            "title" => "Parking",
            "description" => "Ample parking spaces for residents and guests",
            "icon" => "fa-car",
            "display_order" => 6,
        ]);
        ProjectAmenity::create([
            "project_id" => $muroj->id,
            "title" => "Community Center",
            "description" => "Multi-purpose community center for events",
            "icon" => "fa-building",
            "display_order" => 7,
        ]);
        ProjectAmenity::create([
            "project_id" => $muroj->id,
            "title" => "Retail Outlets",
            "description" => "Convenient retail shops within the community",
            "icon" => "fa-shopping-bag",
            "display_order" => 8,
        ]);

        // Add media for MUROJ
        $this->addProjectMedia($muroj, [
            "hero_slider" => [
                "assets/img/random/random (10).png",
                "assets/img/random/random (11).png",
                "assets/img/random/random (12).png",
                "assets/img/random/random (13).png",
            ],
            "hero_thumbnails" => [
                "assets/img/random/random (10).png",
                "assets/img/random/random (11).png",
                "assets/img/random/random (12).png",
                "assets/img/random/random (13).png",
            ],
            "about_image" => "assets/img/random/random (14).png",
            "gallery" => [
                "assets/img/random/random (15).png",
                "assets/img/random/random (16).png",
                "assets/img/random/random (17).png",
                "assets/img/random/random (18).png",
                "assets/img/random/random (19).png",
                "assets/img/random/random (20).png",
            ],
        ]);

        // Project 2: Rich Hills
        $richHills = Project::create([
            "title" => "Rich Hills",
            "slug" => "rich-hills",
            "location" => "6 October City, Egypt",
            "type" => "apartment",
            "status" => "completed",
            "excerpt" =>
                "An upscale residential compound in 6 October City featuring modern villas and townhouses with premium amenities.",
            "description" =>
                '<h2>Project Overview</h2><p>Rich Hills is a prestigious residential development in 6 October City, Egypt, offering a perfect blend of modern living and natural beauty. This upscale compound features luxury villas and townhouses designed to provide residents with a serene and sophisticated lifestyle.</p><h3>Location Advantages</h3><p>Located in the heart of 6 October City, Rich Hills offers excellent connectivity to Cairo and major business districts. The area is known for its green spaces, international schools, and proximity to shopping and entertainment centers.</p><h3>Architectural Design</h3><p>The development showcases contemporary architectural designs with spacious layouts and high-quality finishes. Each villa and townhouse is crafted to maximize privacy and comfort, featuring private gardens and modern amenities.</p><ul><li>Spacious living areas</li><li>Private gardens and terraces</li><li>Energy-efficient designs</li><li>Customizable interiors</li></ul><h3>Amenities and Lifestyle</h3><p>Rich Hills provides a comprehensive range of amenities to enhance daily life. From recreational facilities to wellness centers, the compound ensures residents have everything they need for a fulfilling lifestyle.</p><h3>Investment Potential</h3><p>As a completed project, Rich Hills offers immediate occupancy and strong rental potential. The area\'s growth and demand for quality housing make it an excellent long-term investment.</p>',
            "property_size_min" => 800,
            "property_size_max" => 3500,
            "total_units" => 180,
            "completion_date" => Carbon::parse("2024-06-30"),
            "is_published" => true,
            "is_featured" => true,
            "display_order" => 2,
        ]);
        $richHills->categories()->attach([$residential->id]);

        // Unit Types for Rich Hills (similar to MUROJ)
        ProjectUnitType::create([
            "project_id" => $richHills->id,
            "name" => "Studio Apartment",
            "description" =>
                "Compact and efficient studio with modern finishes",
            "size_min" => 45,
            "size_max" => 60,
            "display_order" => 1,
        ]);
        ProjectUnitType::create([
            "project_id" => $richHills->id,
            "name" => "1-Bedroom Apartment",
            "description" => "Spacious one-bedroom unit with balcony",
            "size_min" => 70,
            "size_max" => 90,
            "display_order" => 2,
        ]);
        ProjectUnitType::create([
            "project_id" => $richHills->id,
            "name" => "2-Bedroom Apartment",
            "description" => "Comfortable two-bedroom apartment with sea views",
            "size_min" => 110,
            "size_max" => 140,
            "display_order" => 3,
        ]);
        ProjectUnitType::create([
            "project_id" => $richHills->id,
            "name" => "3-Bedroom Apartment",
            "description" =>
                "Luxurious three-bedroom unit with premium amenities",
            "size_min" => 160,
            "size_max" => 200,
            "display_order" => 4,
        ]);
        ProjectUnitType::create([
            "project_id" => $richHills->id,
            "name" => "Penthouse",
            "description" => "Exclusive penthouse with panoramic views",
            "size_min" => 250,
            "size_max" => 350,
            "display_order" => 5,
        ]);

        // Amenities for Rich Hills (same as MUROJ)
        ProjectAmenity::create([
            "project_id" => $richHills->id,
            "title" => "Swimming Pool",
            "description" => "Refreshing swimming pool for residents",
            "icon" => "fa-swimming-pool",
            "display_order" => 1,
        ]);
        ProjectAmenity::create([
            "project_id" => $richHills->id,
            "title" => "Fitness Center",
            "description" => "State-of-the-art gym with modern equipment",
            "icon" => "fa-dumbbell",
            "display_order" => 2,
        ]);
        ProjectAmenity::create([
            "project_id" => $richHills->id,
            "title" => 'Children\'s Play Area',
            "description" => "Safe and fun play area for kids",
            "icon" => "fa-child",
            "display_order" => 3,
        ]);
        ProjectAmenity::create([
            "project_id" => $richHills->id,
            "title" => "Landscaped Gardens",
            "description" =>
                "Beautifully maintained gardens throughout the community",
            "icon" => "fa-leaf",
            "display_order" => 4,
        ]);
        ProjectAmenity::create([
            "project_id" => $richHills->id,
            "title" => "24/7 Security",
            "description" => "Round-the-clock security for peace of mind",
            "icon" => "fa-shield-alt",
            "display_order" => 5,
        ]);
        ProjectAmenity::create([
            "project_id" => $richHills->id,
            "title" => "Parking",
            "description" => "Ample parking spaces for residents and guests",
            "icon" => "fa-car",
            "display_order" => 6,
        ]);
        ProjectAmenity::create([
            "project_id" => $richHills->id,
            "title" => "Community Center",
            "description" => "Multi-purpose community center for events",
            "icon" => "fa-building",
            "display_order" => 7,
        ]);
        ProjectAmenity::create([
            "project_id" => $richHills->id,
            "title" => "Retail Outlets",
            "description" => "Convenient retail shops within the community",
            "icon" => "fa-shopping-bag",
            "display_order" => 8,
        ]);

        // Add media for Rich Hills
        $this->addProjectMedia($richHills, [
            "hero_slider" => [
                "assets/img/random/random (21).png",
                "assets/img/random/random (22).png",
                "assets/img/random/random (23).png",
                "assets/img/random/random (24).png",
            ],
            "hero_thumbnails" => [
                "assets/img/random/random (21).png",
                "assets/img/random/random (22).png",
                "assets/img/random/random (23).png",
                "assets/img/random/random (24).png",
            ],
            "about_image" => "assets/img/random/random (25).png",
            "gallery" => [
                "assets/img/random/random (26).png",
                "assets/img/random/random (27).png",
                "assets/img/random/random (28).png",
                "assets/img/random/random (2).png",
                "assets/img/random/random (3).png",
                "assets/img/random/random (4).png",
            ],
        ]);

        // Project 3: Maldives
        $maldives = Project::create([
            "title" => "Maldives",
            "slug" => "maldives",
            "location" => "Hurghada, Egypt",
            "type" => "villa",
            "status" => "in_progress",
            "excerpt" =>
                "A coastal resort-style residential development in Hurghada inspired by Maldivian architecture and lifestyle.",
            "description" =>
                '<h2>Project Overview</h2><p>Maldives brings the essence of tropical paradise to Hurghada, Egypt. This coastal resort-style development features overwater villas and beachfront properties, inspired by the serene beauty of the Maldives. Residents can enjoy a lifestyle of luxury and relaxation by the Red Sea.</p><h3>Location Advantages</h3><p>Hurghada\'s prime location on the Red Sea coast offers stunning beaches, clear waters, and a thriving tourism industry. The area provides easy access to international airports and is a gateway to Egypt\'s most popular coastal destinations.</p><h3>Architectural Design</h3><p>The project incorporates Maldivian-inspired architecture with modern elements, featuring overwater bungalows, beach villas, and resort-style apartments. Each unit is designed to maximize ocean views and provide a sense of seclusion.</p><ul><li>Overwater villas with private docks</li><li>Beachfront properties</li><li>Tropical landscaping</li><li>Eco-friendly materials</li></ul><h3>Amenities and Lifestyle</h3><p>Maldives offers resort-like amenities including private beaches, water sports facilities, and wellness centers. The development creates a community focused on leisure, health, and environmental sustainability.</p><h3>Investment Potential</h3><p>With Hurghada\'s growing appeal as a tourist destination, Maldives presents strong investment opportunities. The unique concept and high demand for coastal properties make it an attractive option for investors.</p>',
            "property_size_min" => 900,
            "property_size_max" => 2800,
            "total_units" => 320,
            "completion_date" => Carbon::parse("2025-09-30"),
            "is_published" => true,
            "is_featured" => true,
            "display_order" => 3,
        ]);
        $maldives->categories()->attach([$residential->id, $investment->id]);

        // Unit Types for Maldives (similar)
        ProjectUnitType::create([
            "project_id" => $maldives->id,
            "name" => "Studio Apartment",
            "description" =>
                "Compact and efficient studio with modern finishes",
            "size_min" => 45,
            "size_max" => 60,
            "display_order" => 1,
        ]);
        ProjectUnitType::create([
            "project_id" => $maldives->id,
            "name" => "1-Bedroom Apartment",
            "description" => "Spacious one-bedroom unit with balcony",
            "size_min" => 70,
            "size_max" => 90,
            "display_order" => 2,
        ]);
        ProjectUnitType::create([
            "project_id" => $maldives->id,
            "name" => "2-Bedroom Apartment",
            "description" => "Comfortable two-bedroom apartment with sea views",
            "size_min" => 110,
            "size_max" => 140,
            "display_order" => 3,
        ]);
        ProjectUnitType::create([
            "project_id" => $maldives->id,
            "name" => "3-Bedroom Apartment",
            "description" =>
                "Luxurious three-bedroom unit with premium amenities",
            "size_min" => 160,
            "size_max" => 200,
            "display_order" => 4,
        ]);
        ProjectUnitType::create([
            "project_id" => $maldives->id,
            "name" => "Penthouse",
            "description" => "Exclusive penthouse with panoramic views",
            "size_min" => 250,
            "size_max" => 350,
            "display_order" => 5,
        ]);

        // Amenities for Maldives (same)
        ProjectAmenity::create([
            "project_id" => $maldives->id,
            "title" => "Swimming Pool",
            "description" => "Refreshing swimming pool for residents",
            "icon" => "fa-swimming-pool",
            "display_order" => 1,
        ]);
        ProjectAmenity::create([
            "project_id" => $maldives->id,
            "title" => "Fitness Center",
            "description" => "State-of-the-art gym with modern equipment",
            "icon" => "fa-dumbbell",
            "display_order" => 2,
        ]);
        ProjectAmenity::create([
            "project_id" => $maldives->id,
            "title" => 'Children\'s Play Area',
            "description" => "Safe and fun play area for kids",
            "icon" => "fa-child",
            "display_order" => 3,
        ]);
        ProjectAmenity::create([
            "project_id" => $maldives->id,
            "title" => "Landscaped Gardens",
            "description" =>
                "Beautifully maintained gardens throughout the community",
            "icon" => "fa-leaf",
            "display_order" => 4,
        ]);
        ProjectAmenity::create([
            "project_id" => $maldives->id,
            "title" => "24/7 Security",
            "description" => "Round-the-clock security for peace of mind",
            "icon" => "fa-shield-alt",
            "display_order" => 5,
        ]);
        ProjectAmenity::create([
            "project_id" => $maldives->id,
            "title" => "Parking",
            "description" => "Ample parking spaces for residents and guests",
            "icon" => "fa-car",
            "display_order" => 6,
        ]);
        ProjectAmenity::create([
            "project_id" => $maldives->id,
            "title" => "Community Center",
            "description" => "Multi-purpose community center for events",
            "icon" => "fa-building",
            "display_order" => 7,
        ]);
        ProjectAmenity::create([
            "project_id" => $maldives->id,
            "title" => "Retail Outlets",
            "description" => "Convenient retail shops within the community",
            "icon" => "fa-shopping-bag",
            "display_order" => 8,
        ]);

        // Add media for Maldives
        $this->addProjectMedia($maldives, [
            "hero_slider" => [
                "assets/img/random/random (5).png",
                "assets/img/random/random (6).png",
                "assets/img/random/random (7).png",
                "assets/img/random/random (8).png",
            ],
            "hero_thumbnails" => [
                "assets/img/random/random (5).png",
                "assets/img/random/random (6).png",
                "assets/img/random/random (7).png",
                "assets/img/random/random (8).png",
            ],
            "about_image" => "assets/img/random/random (9).png",
            "gallery" => [
                "assets/img/random/random (10).png",
                "assets/img/random/random (11).png",
                "assets/img/random/random (12).png",
                "assets/img/random/random (13).png",
                "assets/img/random/random (14).png",
                "assets/img/random/random (15).png",
            ],
        ]);

        // Project 4: RAS AL KHAIMAH
        $rasAlKhaimah = Project::create([
            "title" => "Ras Al Khaimah Towers",
            "slug" => "ras-al-khaimah-towers",
            "location" => "Ras Al Khaimah, UAE",
            "type" => "mixed_use",
            "status" => "upcoming",
            "excerpt" =>
                "A mixed-use development in Ras Al Khaimah combining residential, commercial, and hospitality spaces.",
            "description" =>
                "<h2>Project Overview</h2><p>Ras Al Khaimah Towers is a visionary mixed-use development in Ras Al Khaimah, UAE, blending residential, commercial, and hospitality elements. This project aims to create a vibrant urban center that caters to modern lifestyles and business needs.</p><h3>Location Advantages</h3><p>Located in the heart of Ras Al Khaimah, the development benefits from proximity to key infrastructure, including highways, airports, and business districts. The area is experiencing rapid growth as a commercial and tourism hub.</p><h3>Architectural Design</h3><p>The towers feature contemporary designs with sustainable materials and smart technology integration. The mixed-use concept includes high-rise residential units, office spaces, retail areas, and hotel facilities.</p><ul><li>High-rise residential towers</li><li>Commercial office spaces</li><li>Retail and entertainment zones</li><li>Hospitality suites</li></ul><h3>Amenities and Lifestyle</h3><p>Ras Al Khaimah Towers offers comprehensive amenities including rooftop gardens, fitness centers, and business lounges. The development fosters a community that supports work-life balance and urban living.</p><h3>Investment Potential</h3><p>As a mixed-use project in a growing emirate, Ras Al Khaimah Towers provides diverse investment opportunities in residential, commercial, and hospitality sectors. The strategic location ensures long-term value appreciation.</p>",
            "property_size_min" => 600,
            "property_size_max" => 5000,
            "total_units" => 450,
            "completion_date" => Carbon::parse("2027-12-31"),
            "is_published" => true,
            "is_featured" => true,
            "display_order" => 4,
        ]);
        $rasAlKhaimah
            ->categories()
            ->attach([$mixedUse->id, $commercial->id, $investment->id]);

        // Unit Types for RAS AL KHAIMAH (assuming similar for mixed-use)
        ProjectUnitType::create([
            "project_id" => $rasAlKhaimah->id,
            "name" => "Studio Apartment",
            "description" =>
                "Compact and efficient studio with modern finishes",
            "size_min" => 45,
            "size_max" => 60,
            "display_order" => 1,
        ]);
        ProjectUnitType::create([
            "project_id" => $rasAlKhaimah->id,
            "name" => "1-Bedroom Apartment",
            "description" => "Spacious one-bedroom unit with balcony",
            "size_min" => 70,
            "size_max" => 90,
            "display_order" => 2,
        ]);
        ProjectUnitType::create([
            "project_id" => $rasAlKhaimah->id,
            "name" => "2-Bedroom Apartment",
            "description" => "Comfortable two-bedroom apartment with sea views",
            "size_min" => 110,
            "size_max" => 140,
            "display_order" => 3,
        ]);
        ProjectUnitType::create([
            "project_id" => $rasAlKhaimah->id,
            "name" => "3-Bedroom Apartment",
            "description" =>
                "Luxurious three-bedroom unit with premium amenities",
            "size_min" => 160,
            "size_max" => 200,
            "display_order" => 4,
        ]);
        ProjectUnitType::create([
            "project_id" => $rasAlKhaimah->id,
            "name" => "Penthouse",
            "description" => "Exclusive penthouse with panoramic views",
            "size_min" => 250,
            "size_max" => 350,
            "display_order" => 5,
        ]);

        // Amenities for RAS AL KHAIMAH (same)
        ProjectAmenity::create([
            "project_id" => $rasAlKhaimah->id,
            "title" => "Swimming Pool",
            "description" => "Refreshing swimming pool for residents",
            "icon" => "fa-swimming-pool",
            "display_order" => 1,
        ]);
        ProjectAmenity::create([
            "project_id" => $rasAlKhaimah->id,
            "title" => "Fitness Center",
            "description" => "State-of-the-art gym with modern equipment",
            "icon" => "fa-dumbbell",
            "display_order" => 2,
        ]);
        ProjectAmenity::create([
            "project_id" => $rasAlKhaimah->id,
            "title" => 'Children\'s Play Area',
            "description" => "Safe and fun play area for kids",
            "icon" => "fa-child",
            "display_order" => 3,
        ]);
        ProjectAmenity::create([
            "project_id" => $rasAlKhaimah->id,
            "title" => "Landscaped Gardens",
            "description" =>
                "Beautifully maintained gardens throughout the community",
            "icon" => "fa-leaf",
            "display_order" => 4,
        ]);
        ProjectAmenity::create([
            "project_id" => $rasAlKhaimah->id,
            "title" => "24/7 Security",
            "description" => "Round-the-clock security for peace of mind",
            "icon" => "fa-shield-alt",
            "display_order" => 5,
        ]);
        ProjectAmenity::create([
            "project_id" => $rasAlKhaimah->id,
            "title" => "Parking",
            "description" => "Ample parking spaces for residents and guests",
            "icon" => "fa-car",
            "display_order" => 6,
        ]);
        ProjectAmenity::create([
            "project_id" => $rasAlKhaimah->id,
            "title" => "Community Center",
            "description" => "Multi-purpose community center for events",
            "icon" => "fa-building",
            "display_order" => 7,
        ]);
        ProjectAmenity::create([
            "project_id" => $rasAlKhaimah->id,
            "title" => "Retail Outlets",
            "description" => "Convenient retail shops within the community",
            "icon" => "fa-shopping-bag",
            "display_order" => 8,
        ]);

        // Add media for Ras Al Khaimah Towers
        $this->addProjectMedia($rasAlKhaimah, [
            "hero_slider" => [
                "assets/img/random/geometric1.png",
                "assets/img/random/geometric3.png",
                "assets/img/random/h4-img-4.png",
                "assets/img/random/rand.png",
            ],
            "hero_thumbnails" => [
                "assets/img/random/geometric1.png",
                "assets/img/random/geometric3.png",
                "assets/img/random/h4-img-4.png",
                "assets/img/random/rand.png",
            ],
            "about_image" => "assets/img/random/rand.png",
            "gallery" => [
                "assets/img/random/randdom.png",
                "assets/img/random/random.png",
                "assets/img/random/random-girl.png",
                "assets/img/random/random-project.png",
                "assets/img/random/random (16).png",
                "assets/img/random/random (17).png",
            ],
        ]);

        // Project 5: Sheikh Zayed Residence
        $sheikhZayed = Project::create([
            "title" => "Sheikh Zayed Residence",
            "slug" => "sheikh-zayed-residence",
            "location" => "Sheikh Zayed, Egypt",
            "type" => "apartment",
            "status" => "in_progress",
            "excerpt" =>
                "A smart residential community in Sheikh Zayed featuring integrated technology and sustainable design.",
            "description" =>
                '<h2>Project Overview</h2><p>Sheikh Zayed Residence is a forward-thinking residential community in Sheikh Zayed, Egypt, emphasizing smart technology and sustainable living. This development integrates cutting-edge innovations with eco-friendly designs to create a modern, efficient lifestyle.</p><h3>Location Advantages</h3><p>Situated in Sheikh Zayed, the project offers proximity to Cairo\'s business districts, international schools, and healthcare facilities. The area is known for its planned infrastructure and green spaces.</p><h3>Architectural Design</h3><p>The residences feature smart home systems, energy-efficient materials, and sustainable building practices. Each unit is designed for comfort, with integrated technology for security, entertainment, and energy management.</p><ul><li>Smart home automation</li><li>Solar panel integration</li><li>Water conservation systems</li><li>Green building certifications</li></ul><h3>Amenities and Lifestyle</h3><p>Sheikh Zayed Residence provides amenities focused on wellness and technology, including smart gyms, community gardens, and tech-enabled common areas. The development promotes a healthy, connected lifestyle.</p><h3>Investment Potential</h3><p>As a smart community in a developing area, Sheikh Zayed Residence offers strong investment potential. The focus on technology and sustainability appeals to modern buyers and investors seeking future-proof properties.</p>',
            "property_size_min" => 1000,
            "property_size_max" => 3800,
            "total_units" => 200,
            "completion_date" => Carbon::parse("2026-03-31"),
            "is_published" => true,
            "is_featured" => false,
            "display_order" => 5,
        ]);
        $sheikhZayed->categories()->attach([$residential->id]);

        // Unit Types for Sheikh Zayed Residence (similar)
        ProjectUnitType::create([
            "project_id" => $sheikhZayed->id,
            "name" => "Studio Apartment",
            "description" =>
                "Compact and efficient studio with modern finishes",
            "size_min" => 45,
            "size_max" => 60,
            "display_order" => 1,
        ]);
        ProjectUnitType::create([
            "project_id" => $sheikhZayed->id,
            "name" => "1-Bedroom Apartment",
            "description" => "Spacious one-bedroom unit with balcony",
            "size_min" => 70,
            "size_max" => 90,
            "display_order" => 2,
        ]);
        ProjectUnitType::create([
            "project_id" => $sheikhZayed->id,
            "name" => "2-Bedroom Apartment",
            "description" => "Comfortable two-bedroom apartment with sea views",
            "size_min" => 110,
            "size_max" => 140,
            "display_order" => 3,
        ]);
        ProjectUnitType::create([
            "project_id" => $sheikhZayed->id,
            "name" => "3-Bedroom Apartment",
            "description" =>
                "Luxurious three-bedroom unit with premium amenities",
            "size_min" => 160,
            "size_max" => 200,
            "display_order" => 4,
        ]);
        ProjectUnitType::create([
            "project_id" => $sheikhZayed->id,
            "name" => "Penthouse",
            "description" => "Exclusive penthouse with panoramic views",
            "size_min" => 250,
            "size_max" => 350,
            "display_order" => 5,
        ]);

        // Amenities for Sheikh Zayed Residence (same)
        ProjectAmenity::create([
            "project_id" => $sheikhZayed->id,
            "title" => "Swimming Pool",
            "description" => "Refreshing swimming pool for residents",
            "icon" => "fa-swimming-pool",
            "display_order" => 1,
        ]);
        ProjectAmenity::create([
            "project_id" => $sheikhZayed->id,
            "title" => "Fitness Center",
            "description" => "State-of-the-art gym with modern equipment",
            "icon" => "fa-dumbbell",
            "display_order" => 2,
        ]);
        ProjectAmenity::create([
            "project_id" => $sheikhZayed->id,
            "title" => 'Children\'s Play Area',
            "description" => "Safe and fun play area for kids",
            "icon" => "fa-child",
            "display_order" => 3,
        ]);
        ProjectAmenity::create([
            "project_id" => $sheikhZayed->id,
            "title" => "Landscaped Gardens",
            "description" =>
                "Beautifully maintained gardens throughout the community",
            "icon" => "fa-leaf",
            "display_order" => 4,
        ]);
        ProjectAmenity::create([
            "project_id" => $sheikhZayed->id,
            "title" => "24/7 Security",
            "description" => "Round-the-clock security for peace of mind",
            "icon" => "fa-shield-alt",
            "display_order" => 5,
        ]);
        ProjectAmenity::create([
            "project_id" => $sheikhZayed->id,
            "title" => "Parking",
            "description" => "Ample parking spaces for residents and guests",
            "icon" => "fa-car",
            "display_order" => 6,
        ]);
        ProjectAmenity::create([
            "project_id" => $sheikhZayed->id,
            "title" => "Community Center",
            "description" => "Multi-purpose community center for events",
            "icon" => "fa-building",
            "display_order" => 7,
        ]);
        ProjectAmenity::create([
            "project_id" => $sheikhZayed->id,
            "title" => "Retail Outlets",
            "description" => "Convenient retail shops within the community",
            "icon" => "fa-shopping-bag",
            "display_order" => 8,
        ]);

        // Add media for Sheikh Zayed Residence
        $this->addProjectMedia($sheikhZayed, [
            "hero_slider" => [
                "assets/img/random/random (25).png",
                "assets/img/random/random (26).png",
                "assets/img/random/random (27).png",
                "assets/img/random/random (28).png",
            ],
            "hero_thumbnails" => [
                "assets/img/random/random (25).png",
                "assets/img/random/random (26).png",
                "assets/img/random/random (27).png",
                "assets/img/random/random (28).png",
            ],
            "about_image" => "assets/img/random/random (18).png",
            "gallery" => [
                "assets/img/random/random (19).png",
                "assets/img/random/random (20).png",
                "assets/img/random/random (21).png",
                "assets/img/random/random (22).png",
                "assets/img/random/random (23).png",
                "assets/img/random/random (24).png",
            ],
        ]);

        // Project 6: Coastal Paradise
        $coastalParadise = Project::create([
            "title" => "Coastal Paradise",
            "slug" => "coastal-paradise",
            "location" => "North Coast, Egypt",
            "type" => "villa",
            "status" => "upcoming",
            "excerpt" =>
                'A luxury beachfront development on Egypt\'s North Coast with world-class amenities and services.',
            "description" =>
                '<h2>Project Overview</h2><p>Coastal Paradise is a luxurious beachfront development on Egypt\'s North Coast, offering residents an unparalleled coastal lifestyle. This project combines high-end residences with world-class amenities, creating a paradise for those seeking luxury by the sea.</p><h3>Location Advantages</h3><p>Located on the pristine North Coast, Coastal Paradise provides access to some of Egypt\'s most beautiful beaches and clear Mediterranean waters. The area is a popular destination for tourism and offers proximity to major cities.</p><h3>Architectural Design</h3><p>The development features contemporary architectural designs inspired by coastal aesthetics. Units include beachfront villas, apartments, and penthouses, all designed to maximize sea views and natural light.</p><ul><li>Beachfront properties</li><li>Private beach access</li><li>Coastal-inspired interiors</li><li>High-end finishes</li></ul><h3>Amenities and Lifestyle</h3><p>Coastal Paradise offers extensive amenities including private beaches, marina facilities, and wellness centers. The development promotes an active, healthy lifestyle with recreational and leisure options.</p><h3>Investment Potential</h3><p>The North Coast\'s growing popularity as a tourist and residential destination makes Coastal Paradise an excellent investment. The luxury positioning and coastal location ensure strong demand and value appreciation.</p>',
            "property_size_min" => 1500,
            "property_size_max" => 6000,
            "total_units" => 280,
            "completion_date" => Carbon::parse("2027-06-30"),
            "is_published" => true,
            "is_featured" => false,
            "display_order" => 6,
        ]);
        $coastalParadise
            ->categories()
            ->attach([$residential->id, $investment->id]);

        // Unit Types for Coastal Paradise (similar)
        ProjectUnitType::create([
            "project_id" => $coastalParadise->id,
            "name" => "Studio Apartment",
            "description" =>
                "Compact and efficient studio with modern finishes",
            "size_min" => 45,
            "size_max" => 60,
            "display_order" => 1,
        ]);
        ProjectUnitType::create([
            "project_id" => $coastalParadise->id,
            "name" => "1-Bedroom Apartment",
            "description" => "Spacious one-bedroom unit with balcony",
            "size_min" => 70,
            "size_max" => 90,
            "display_order" => 2,
        ]);
        ProjectUnitType::create([
            "project_id" => $coastalParadise->id,
            "name" => "2-Bedroom Apartment",
            "description" => "Comfortable two-bedroom apartment with sea views",
            "size_min" => 110,
            "size_max" => 140,
            "display_order" => 3,
        ]);
        ProjectUnitType::create([
            "project_id" => $coastalParadise->id,
            "name" => "3-Bedroom Apartment",
            "description" =>
                "Luxurious three-bedroom unit with premium amenities",
            "size_min" => 160,
            "size_max" => 200,
            "display_order" => 4,
        ]);
        ProjectUnitType::create([
            "project_id" => $coastalParadise->id,
            "name" => "Penthouse",
            "description" => "Exclusive penthouse with panoramic views",
            "size_min" => 250,
            "size_max" => 350,
            "display_order" => 5,
        ]);

        // Amenities for Coastal Paradise (same)
        ProjectAmenity::create([
            "project_id" => $coastalParadise->id,
            "title" => "Swimming Pool",
            "description" => "Refreshing swimming pool for residents",
            "icon" => "fa-swimming-pool",
            "display_order" => 1,
        ]);
        ProjectAmenity::create([
            "project_id" => $coastalParadise->id,
            "title" => "Fitness Center",
            "description" => "State-of-the-art gym with modern equipment",
            "icon" => "fa-dumbbell",
            "display_order" => 2,
        ]);
        ProjectAmenity::create([
            "project_id" => $coastalParadise->id,
            "title" => 'Children\'s Play Area',
            "description" => "Safe and fun play area for kids",
            "icon" => "fa-child",
            "display_order" => 3,
        ]);
        ProjectAmenity::create([
            "project_id" => $coastalParadise->id,
            "title" => "Landscaped Gardens",
            "description" =>
                "Beautifully maintained gardens throughout the community",
            "icon" => "fa-leaf",
            "display_order" => 4,
        ]);
        ProjectAmenity::create([
            "project_id" => $coastalParadise->id,
            "title" => "24/7 Security",
            "description" => "Round-the-clock security for peace of mind",
            "icon" => "fa-shield-alt",
            "display_order" => 5,
        ]);
        ProjectAmenity::create([
            "project_id" => $coastalParadise->id,
            "title" => "Parking",
            "description" => "Ample parking spaces for residents and guests",
            "icon" => "fa-car",
            "display_order" => 6,
        ]);
        ProjectAmenity::create([
            "project_id" => $coastalParadise->id,
            "title" => "Community Center",
            "description" => "Multi-purpose community center for events",
            "icon" => "fa-building",
            "display_order" => 7,
        ]);
        ProjectAmenity::create([
            "project_id" => $coastalParadise->id,
            "title" => "Retail Outlets",
            "description" => "Convenient retail shops within the community",
            "icon" => "fa-shopping-bag",
            "display_order" => 8,
        ]);

        // Add media for COASTAL PARADISE
        $this->addProjectMedia($coastalParadise, [
            "hero_slider" => [
                "assets/img/random/random (7).png",
                "assets/img/random/random (8).png",
                "assets/img/random/random (9).png",
                "assets/img/random/random (10).png",
            ],
            "hero_thumbnails" => [
                "assets/img/random/random (7).png",
                "assets/img/random/random (8).png",
                "assets/img/random/random (9).png",
                "assets/img/random/random (10).png",
            ],
            "about_image" => "assets/img/random/random (11).png",
            "gallery" => [
                "assets/img/random/random (12).png",
                "assets/img/random/random (13).png",
                "assets/img/random/random (14).png",
                "assets/img/random/random (15).png",
                "assets/img/random/random (16).png",
                "assets/img/random/random (17).png",
            ],
        ]);
    }
}
