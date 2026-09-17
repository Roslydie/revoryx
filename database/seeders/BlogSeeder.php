<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::where('email', 'admin@example.com')->firstOrFail();

        $tagIds = collect(['Digital transformation', 'Web development', 'Business growth'])
            ->mapWithKeys(fn (string $name) => [$name => Tag::firstOrCreate(['name' => $name])->id]);

        $blogs = [
            [
                'slug' => 'why-digital-transformation-matters',
                'title' => 'Why digital transformation matters for growing businesses',
                'description' => 'A practical look at how the right digital foundations help teams work smarter and create lasting value.',
                'content' => '<p>Digital transformation is not only about adopting new tools. It is about making better decisions, improving everyday operations and creating experiences that serve people well.</p><p>With a clear strategy and a reliable technical foundation, growing businesses can move faster while keeping their focus on meaningful results.</p>',
                'image_source' => 'blog1.jpg',
                'status' => 'published',
                'tags' => ['Digital transformation', 'Business growth'],
            ],
            [
                'slug' => 'building-a-website-that-works',
                'title' => 'Building a website that works for your business',
                'description' => 'The essential principles behind a website that is useful, accessible and ready to support business growth.',
                'content' => '<p>A strong website connects a clear message with a simple user journey. It should help visitors understand your value and take the next step with confidence.</p><p>Performance, accessibility and maintainability matter just as much as visual design when a website is expected to deliver long-term value.</p>',
                'image_source' => 'blog2.jpg',
                'status' => 'published',
                'tags' => ['Web development', 'Business growth'],
            ],
            [
                'slug' => 'from-idea-to-digital-solution',
                'title' => 'From idea to a focused digital solution',
                'description' => 'How a clear process turns an ambitious idea into a solution people can use and organizations can grow with.',
                'content' => '<p>The best digital projects start with a shared understanding of the problem. From there, teams can prioritize the right features and test assumptions early.</p><p>Focused delivery keeps momentum high and creates room to learn, improve and scale with confidence.</p>',
                'image_source' => 'blog3.jpg',
                'status' => 'draft',
                'tags' => ['Digital transformation', 'Web development'],
            ],
        ];

        foreach ($blogs as $blogData) {
            $blog = Blog::updateOrCreate(
                ['slug' => $blogData['slug']],
                [
                    'image' => $this->copyAsset($blogData['image_source'], 'blogs'),
                    'title' => $blogData['title'],
                    'description' => $blogData['description'],
                    'content' => $blogData['content'],
                    'status' => $blogData['status'],
                    'user_id' => $author->id,
                ]
            );

            $blog->tags()->sync(collect($blogData['tags'])->map(fn (string $tag) => $tagIds[$tag])->all());
        }
    }

    private function copyAsset(string $filename, string $directory): string
    {
        $source = public_path("assets/images/{$filename}");
        $destination = "{$directory}/{$filename}";

        if (is_file($source) && !Storage::disk('public')->exists($destination)) {
            Storage::disk('public')->put($destination, file_get_contents($source));
        }

        return $destination;
    }
}
