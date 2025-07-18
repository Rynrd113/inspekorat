<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSearch;
use App\Traits\HasPagination;

class Faq extends Model
{
    use HasFactory, HasSearch, HasPagination;

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
            'dokumen' => 'Dokumen',
            'galeri' => 'Galeri',
            'kontak' => 'Kontak',
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

    /**
     * Get FAQs by user role context
     */
    public static function getByUserRole($userRole)
    {
        $categoryMap = [
            'admin_wbs' => ['wbs', 'umum'],
            'admin_portal_opd' => ['portal_opd', 'umum'],
            'admin_pelayanan' => ['pelayanan', 'umum'],
            'admin_dokumen' => ['dokumen', 'umum'],
            'admin_galeri' => ['galeri', 'umum'],
            'wbs_manager' => ['wbs', 'umum'],
            'opd_manager' => ['portal_opd', 'umum'],
            'service_manager' => ['pelayanan', 'dokumen', 'kontak', 'umum'],
            'content_manager' => ['galeri', 'umum'],
        ];

        $categories = $categoryMap[$userRole] ?? ['umum'];
        
        return self::active()
            ->whereIn('kategori', $categories)
            ->ordered()
            ->get();
    }

    /**
     * Search FAQs
     */
    public static function search($query)
    {
        return self::active()
            ->where(function($q) use ($query) {
                $q->where('pertanyaan', 'like', "%{$query}%")
                  ->orWhere('jawaban', 'like', "%{$query}%")
                  ->orWhere('tags', 'like', "%{$query}%");
            })
            ->ordered();
    }

    /**
     * Get searchable fields for FAQ
     */
    protected function getSearchableFields(): array
    {
        return [
            'pertanyaan', 'jawaban', 'tags'
        ];
    }

    /**
     * Get filterable fields for FAQ
     */
    protected function getFilterableFields(): array
    {
        return [
            'kategori' => 'exact',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'created_at' => 'date_range',
            'updated_at' => 'date_range',
        ];
    }

    /**
     * Get sortable fields for FAQ
     */
    protected function getSortableFields(): array
    {
        return [
            'id', 'pertanyaan', 'kategori', 'status', 'is_featured', 'urutan', 'view_count', 'created_at', 'updated_at'
        ];
    }
}
