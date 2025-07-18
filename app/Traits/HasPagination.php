<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

trait HasPagination
{
    /**
     * Apply pagination to the query
     */
    public function scopePaginated(Builder $query, Request $request, int $defaultPerPage = 10): \Illuminate\Pagination\LengthAwarePaginator
    {
        $perPage = $request->get('per_page', $defaultPerPage);
        
        // Ensure per_page is within reasonable limits
        $perPage = max(5, min(100, intval($perPage)));
        
        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Apply sorting to the query
     */
    public function scopeSorted(Builder $query, Request $request): Builder
    {
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        // Validate sort direction
        $sortDirection = in_array($sortDirection, ['asc', 'desc']) ? $sortDirection : 'desc';
        
        // Validate sort field (only allow fields that exist in the model)
        $allowedFields = $this->getSortableFields();
        if (in_array($sortField, $allowedFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            // Default sorting
            $query->orderBy('created_at', 'desc');
        }
        
        return $query;
    }

    /**
     * Get sortable fields for the model
     * Override this method in your model to define sortable fields
     */
    protected function getSortableFields(): array
    {
        return [
            'id', 'created_at', 'updated_at', 'name', 'title', 'judul', 'nama', 
            'email', 'status', 'kategori', 'urutan', 'published_at', 'views'
        ];
    }

    /**
     * Get pagination options for display
     */
    public static function getPaginationOptions(): array
    {
        return [
            5 => '5 per halaman',
            10 => '10 per halaman',
            25 => '25 per halaman',
            50 => '50 per halaman',
            100 => '100 per halaman',
        ];
    }

    /**
     * Get sort options for display
     */
    public static function getSortOptions(): array
    {
        return [
            'created_at' => 'Tanggal Dibuat',
            'updated_at' => 'Tanggal Diperbarui',
            'name' => 'Nama',
            'title' => 'Judul',
            'judul' => 'Judul',
            'nama' => 'Nama',
            'email' => 'Email',
            'status' => 'Status',
            'kategori' => 'Kategori',
            'urutan' => 'Urutan',
            'published_at' => 'Tanggal Publikasi',
            'views' => 'Jumlah Dilihat',
        ];
    }
}