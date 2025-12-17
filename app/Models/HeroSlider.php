<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class HeroSlider extends Model
{
    protected $fillable = [
        'judul',
        'subjudul',
        'deskripsi',
        'gambar',
        'link_url',
        'link_text',
        'tanggal_mulai',
        'tanggal_selesai',
        'prioritas',
        'kategori',
        'status',
        'urutan',
        'views',
        'is_active',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
        'views' => 'integer',
        'urutan' => 'integer',
    ];

    /**
     * Query Scopes
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeCurrentDate(Builder $query): Builder
    {
        $today = now()->toDateString();
        return $query->where(function ($q) use ($today) {
            $q->whereNull('tanggal_mulai')
              ->orWhere('tanggal_mulai', '<=', $today);
        })->where(function ($q) use ($today) {
            $q->whereNull('tanggal_selesai')
              ->orWhere('tanggal_selesai', '>=', $today);
        });
    }

    public function scopeByPrioritas(Builder $query, string $prioritas): Builder
    {
        return $query->where('prioritas', $prioritas);
    }

    public function scopeByKategori(Builder $query, string $kategori): Builder
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('urutan', 'asc')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Get active slides for homepage (published, active, current date)
     */
    public function scopeForHomepage(Builder $query, int $limit = 5): Builder
    {
        return $query->published()
                    ->active()
                    ->currentDate()
                    ->ordered()
                    ->limit($limit);
    }

    /**
     * Accessors
     */
    public function getGambarUrlAttribute(): ?string
    {
        if (!$this->gambar) {
            return null;
        }

        // Jika sudah full URL
        if (str_starts_with($this->gambar, 'http')) {
            return $this->gambar;
        }

        $disk = env('STORAGE_DISK', 'public');
        
        if ($disk === 'public_root') {
            return asset('storage/' . $this->gambar);
        }

        return Storage::disk($disk)->url($this->gambar);
    }

    public function getPrioritasBadgeAttribute(): string
    {
        $badges = [
            'urgent' => 'bg-red-100 text-red-800',
            'tinggi' => 'bg-orange-100 text-orange-800',
            'normal' => 'bg-blue-100 text-blue-800',
        ];
        
        return $badges[$this->prioritas] ?? 'bg-gray-100 text-gray-800';
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'published' => 'bg-green-100 text-green-800',
            'draft' => 'bg-yellow-100 text-yellow-800',
            'archived' => 'bg-gray-100 text-gray-800',
        ];
        
        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getKategoriBadgeAttribute(): string
    {
        $badges = [
            'pengumuman' => 'bg-purple-100 text-purple-800',
            'event' => 'bg-blue-100 text-blue-800',
            'layanan' => 'bg-green-100 text-green-800',
            'berita' => 'bg-indigo-100 text-indigo-800',
        ];
        
        return $badges[$this->kategori] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Increment views counter
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    /**
     * Check if slider is currently valid (within date range)
     */
    public function isCurrentlyValid(): bool
    {
        $today = now()->toDateString();

        $startValid = !$this->tanggal_mulai || $this->tanggal_mulai->lte($today);
        $endValid = !$this->tanggal_selesai || $this->tanggal_selesai->gte($today);

        return $startValid && $endValid;
    }
}
