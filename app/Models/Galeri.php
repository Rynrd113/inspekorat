<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    protected $table = 'galeris';

    protected $fillable = [
        'judul',
        'deskripsi',
        'kategori',
        'album',
        'tanggal_kegiatan',
        'lokasi_kegiatan',
        'file_media',
        'thumbnail',
        'status',
        'is_featured',
        'tags',
        'view_count',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'view_count' => 'integer',
        'tags' => 'json',
    ];

    /**
     * Scope for active gallery items
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope for featured gallery items
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for specific category
     */
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Scope for specific album
     */
    public function scopeByAlbum($query, $album)
    {
        return $query->where('album', $album);
    }

    /**
     * Scope for photos only
     */
    public function scopeFoto($query)
    {
        return $query->where('kategori', 'foto');
    }

    /**
     * Scope for videos only
     */
    public function scopeVideo($query)
    {
        return $query->where('kategori', 'video');
    }

    /**
     * Get the user who created this gallery item
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this gallery item
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get available gallery categories
     */
    public static function getKategoriOptions()
    {
        return [
            'foto' => 'Foto',
            'video' => 'Video',
        ];
    }

    /**
     * Get kategori label
     */
    public function getKategoriLabelAttribute()
    {
        $options = self::getKategoriOptions();
        return $options[$this->kategori] ?? $this->kategori;
    }

    /**
     * Increment view count
     */
    public function incrementView()
    {
        $this->increment('view_count');
    }

    /**
     * Get file extension
     */
    public function getFileExtensionAttribute()
    {
        return pathinfo($this->file_media, PATHINFO_EXTENSION);
    }

    /**
     * Check if media is image
     */
    public function getIsImageAttribute()
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        return in_array(strtolower($this->file_extension), $imageExtensions);
    }

    /**
     * Check if media is video
     */
    public function getIsVideoAttribute()
    {
        $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv'];
        return in_array(strtolower($this->file_extension), $videoExtensions);
    }
}
