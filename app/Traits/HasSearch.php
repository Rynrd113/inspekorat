<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait HasSearch
{
    /**
     * Apply search filters to the query
     */
    public function scopeSearch(Builder $query, Request $request): Builder
    {
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $searchFields = $this->getSearchableFields();
            
            $query->where(function (Builder $q) use ($searchTerm, $searchFields) {
                foreach ($searchFields as $field) {
                    $q->orWhere($field, 'like', "%{$searchTerm}%");
                }
            });
        }

        return $query;
    }

    /**
     * Apply filters to the query
     */
    public function scopeFilter(Builder $query, Request $request): Builder
    {
        $filterableFields = $this->getFilterableFields();
        
        foreach ($filterableFields as $field => $type) {
            if ($request->filled($field)) {
                $value = $request->get($field);
                
                switch ($type) {
                    case 'exact':
                        $query->where($field, $value);
                        break;
                    case 'like':
                        $query->where($field, 'like', "%{$value}%");
                        break;
                    case 'date':
                        $query->whereDate($field, $value);
                        break;
                    case 'date_range':
                        if ($request->filled($field . '_from')) {
                            $query->whereDate($field, '>=', $request->get($field . '_from'));
                        }
                        if ($request->filled($field . '_to')) {
                            $query->whereDate($field, '<=', $request->get($field . '_to'));
                        }
                        break;
                    case 'boolean':
                        $query->where($field, filter_var($value, FILTER_VALIDATE_BOOLEAN));
                        break;
                    default:
                        $query->where($field, $value);
                }
            }
        }

        return $query;
    }

    /**
     * Get searchable fields for the model
     * Override this method in your model to define searchable fields
     */
    protected function getSearchableFields(): array
    {
        return [
            'name', 'title', 'judul', 'nama', 'subjek', 'pertanyaan', 'nama_opd', 'deskripsi'
        ];
    }

    /**
     * Get filterable fields for the model
     * Override this method in your model to define filterable fields
     */
    protected function getFilterableFields(): array
    {
        return [
            'status' => 'exact',
            'kategori' => 'exact',
            'is_published' => 'boolean',
            'is_active' => 'boolean',
            'created_at' => 'date_range',
            'updated_at' => 'date_range',
        ];
    }
}