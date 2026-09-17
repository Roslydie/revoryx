<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::where('email', 'admin@example.com')->firstOrFail();

        $categoryIds = collect(['Web development', 'Digital strategy', 'Business solutions'])
            ->mapWithKeys(fn (string $name) => [$name => Category::firstOrCreate(['name' => $name])->id]);

        $projects = [
            [
                'slug' => 'northstar-business-platform',
                'category' => 'Business solutions',
                'title' => 'Northstar Business Platform',
                'client' => 'Northstar Group',
                'location' => 'Philadelphia, PA',
                'years' => '2025',
                'images' => ['1.jpg', '2.jpg'],
                'description' => 'A focused digital platform that brings essential business operations into one clear workspace.',
                'content' => '<p>Northstar needed a dependable platform to simplify internal workflows and give its teams a clearer view of daily operations.</p><p>We shaped an intuitive experience around the needs of administrators, managers and field teams.</p>',
                'status' => 'published',
            ],
            [
                'slug' => 'meridian-digital-presence',
                'category' => 'Web development',
                'title' => 'Meridian Digital Presence',
                'client' => 'Meridian Partners',
                'location' => 'New York, NY',
                'years' => '2024',
                'images' => ['3.jpg', '4.jpg'],
                'description' => 'A modern website experience designed to clarify the brand and turn interest into meaningful conversations.',
                'content' => '<p>Meridian needed a digital presence that could communicate its expertise with more clarity and confidence.</p><p>The result combines a refined visual language, a clearer content structure and a flexible foundation for future growth.</p>',
                'status' => 'published',
            ],
            [
                'slug' => 'atlas-growth-roadmap',
                'category' => 'Digital strategy',
                'title' => 'Atlas Growth Roadmap',
                'client' => 'Atlas Ventures',
                'location' => 'Washington, DC',
                'years' => '2025',
                'images' => ['5.jpg', '6.jpg'],
                'description' => 'A practical transformation roadmap connecting technology choices to measurable business priorities.',
                'content' => '<p>Atlas wanted to move from scattered digital initiatives to a shared, actionable direction.</p><p>We mapped the opportunities, clarified priorities and created a roadmap that supports informed decisions over time.</p>',
                'status' => 'draft',
            ],
        ];

        foreach ($projects as $projectData) {
            $project = Project::updateOrCreate(
                ['slug' => $projectData['slug']],
                [
                    'category_id' => $categoryIds[$projectData['category']],
                    'title' => $projectData['title'],
                    'client' => $projectData['client'],
                    'location' => $projectData['location'],
                    'years' => $projectData['years'],
                    'images' => collect($projectData['images'])
                        ->map(fn (string $image) => $this->copyAsset($image))
                        ->all(),
                    'description' => $projectData['description'],
                    'content' => $projectData['content'],
                    'status' => $projectData['status'],
                    'user_id' => $author->id,
                ]
            );
        }
    }

    private function copyAsset(string $filename): string
    {
        $source = public_path("assets/images/galery/{$filename}");
        $destination = "projects/{$filename}";

        if (is_file($source) && !Storage::disk('public')->exists($destination)) {
            Storage::disk('public')->put($destination, file_get_contents($source));
        }

        return $destination;
    }
}
