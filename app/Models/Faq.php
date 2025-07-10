<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $table = 'faqs';

    protected $fillable = [
        'pertanyaan',
        'jawaban',
        'kategori',
        'urutan',
        'status',
        'is_featured',
        'tags',
        'view_count',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'urutan' => 'integer',
        'view_count' => 'integer',
    ];

    /**
     * Scope for active FAQs
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope for featured FAQs
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
     * Scope for ordered FAQs
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('created_at');
    }

    /**
     * Get the user who created this FAQ
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this FAQ
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get available FAQ categories
     */
    public static function getKategoriOptions()
    {
        return [
            'umum' => 'Umum',
            'pelayanan' => 'Pelayanan',
            'pengawasan' => 'Pengawasan',
            'wbs' => 'Whistleblowing System',
            'portal_opd' => 'Portal OPD',
            'teknis' => 'Teknis',
            'lainnya' => 'Lainnya',
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
}
