<?php

namespace App\Observers;

use App\Models\HeroSlider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class HeroSliderObserver
{
    /**
     * Handle the HeroSlider "creating" event.
     */
    public function creating(HeroSlider $heroSlider): void
    {
        // Set urutan otomatis jika belum diset
        if (!$heroSlider->urutan) {
            $heroSlider->urutan = HeroSlider::max('urutan') + 1;
        }
    }

    /**
     * Handle the HeroSlider "created" event.
     */
    public function created(HeroSlider $heroSlider): void
    {
        // Clear cache setelah create
        Cache::forget('hero_sliders_homepage');
        
        \Log::info('Hero Slider created', ['id' => $heroSlider->id, 'judul' => $heroSlider->judul]);
    }

    /**
     * Handle the HeroSlider "updated" event.
     */
    public function updated(HeroSlider $heroSlider): void
    {
        // Clear cache setelah update
        Cache::forget('hero_sliders_homepage');
        
        \Log::info('Hero Slider updated', ['id' => $heroSlider->id, 'judul' => $heroSlider->judul]);
    }

    /**
     * Handle the HeroSlider "deleted" event.
     */
    public function deleted(HeroSlider $heroSlider): void
    {
        // Delete gambar jika ada
        if ($heroSlider->gambar && !str_starts_with($heroSlider->gambar, 'http')) {
            $disk = env('STORAGE_DISK', 'public');
            Storage::disk($disk)->delete($heroSlider->gambar);
        }

        // Clear cache setelah delete
        Cache::forget('hero_sliders_homepage');
        
        \Log::info('Hero Slider deleted', ['id' => $heroSlider->id, 'judul' => $heroSlider->judul]);
    }

    /**
     * Handle the HeroSlider "restored" event.
     */
    public function restored(HeroSlider $heroSlider): void
    {
        Cache::forget('hero_sliders_homepage');
    }

    /**
     * Handle the HeroSlider "force deleted" event.
     */
    public function forceDeleted(HeroSlider $heroSlider): void
    {
        // Delete gambar jika ada
        if ($heroSlider->gambar && !str_starts_with($heroSlider->gambar, 'http')) {
            $disk = env('STORAGE_DISK', 'public');
            Storage::disk($disk)->delete($heroSlider->gambar);
        }

        Cache::forget('hero_sliders_homepage');
    }
}
