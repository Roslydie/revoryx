<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'prenom' => 'Jordan',
                'nom' => 'Miller',
                'message' => 'Revoryx helped us turn a complex digital challenge into a clear, reliable solution. The team was thoughtful, practical and committed from the first conversation to delivery.',
                'published' => true,
            ],
            [
                'prenom' => 'Amelia',
                'nom' => 'Carter',
                'message' => 'We now have a digital experience that feels much more aligned with our business and our customers. Communication was clear, responsive and genuinely collaborative.',
                'published' => true,
            ],
            [
                'prenom' => 'Marcus',
                'nom' => 'Johnson',
                'message' => 'The Revoryx team brought structure to our ideas and helped us focus on what would create the most value. We are very happy with the result and the foundation it gives us for growth.',
                'published' => true,
            ],
            [
                'prenom' => 'Sophia',
                'nom' => 'Williams',
                'message' => 'A strong first conversation gave us a much clearer direction for our next phase of digital improvement.',
                'published' => false,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::firstOrCreate(
                [
                    'prenom' => $testimonial['prenom'],
                    'nom' => $testimonial['nom'],
                    'message' => $testimonial['message'],
                ],
                ['published' => $testimonial['published']]
            );
        }
    }
}
