<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HeroSlider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class HeroSliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if hero sliders already exist
        if (HeroSlider::count() > 0) {
            $this->command->info('Hero sliders already exist. Skipping seeder.');
            return;
        }

        // Copy default image to storage if it doesn't exist
        $defaultImagePath = public_path('landing/registerImage2.png');
        $defaultImagePath2 = public_path('landing/landingImage02.png');
        
        if (File::exists($defaultImagePath)) {
            // Ensure directory exists
            Storage::disk('public')->makeDirectory('hero-slider');
            
            // Copy image to storage
            $storagePath = 'hero-slider/registerImage2.png';
            if (!Storage::disk('public')->exists($storagePath)) {
                File::copy($defaultImagePath, storage_path('app/public/' . $storagePath));
            }
            
            HeroSlider::create([
                'image_path' => $storagePath,
                'title' => 'Default Hero Image',
                'description' => 'Default hero section image',
                'order' => 1,
                'is_active' => true,
            ]);
        }

        if (File::exists($defaultImagePath2)) {
            $storagePath2 = 'hero-slider/landingImage02.png';
            if (!Storage::disk('public')->exists($storagePath2)) {
                File::copy($defaultImagePath2, storage_path('app/public/' . $storagePath2));
            }
            
            HeroSlider::create([
                'image_path' => $storagePath2,
                'title' => 'Hero Image 2',
                'description' => 'Second hero section image',
                'order' => 2,
                'is_active' => true,
            ]);
        }

        $this->command->info('Hero slider images seeded successfully!');
    }
}
