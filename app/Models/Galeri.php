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
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'status',
        'tanggal_publikasi',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_publikasi' => 'date',
        'status' => 'boolean',
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
        return $query->where('status', true);
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
        return $query->where('kategori', $album);
    }

    /**
     * Scope for photos only
     */
    public function scopeFoto($query)
    {
        return $query->whereIn('file_type', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }

    /**
     * Scope for videos only
     */
    public function scopeVideo($query)
    {
        return $query->whereIn('file_type', ['mp4', 'avi', 'mov', 'wmv', 'flv']);
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
        return pathinfo($this->file_path, PATHINFO_EXTENSION);
    }

    /**
     * Check if media is image
     */
    public function getIsImageAttribute()
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        return in_array(strtolower($this->file_type), $imageExtensions);
    }

    /**
     * Check if media is video
     */
    public function getIsVideoAttribute()
    {
        $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv'];
        return in_array(strtolower($this->file_type), $videoExtensions);
    }

    /**
     * Get tipe attribute (alias for file_type compatibility)
     */
    public function getTipeAttribute()
    {
        return $this->file_type;
    }
}
