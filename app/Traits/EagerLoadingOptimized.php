<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

trait EagerLoadingOptimized
{
    /**
     * Default relationships yang akan di-load secara eager (fallback)
     */
    private $traitDefaultEagerLoad = [];

    /**
     * Get contextual eager loading relationships based on context
     */
    protected function getContextualEagerLoad(): array
    {
        return property_exists($this, 'contextualEagerLoad') ? $this->contextualEagerLoad : [
            'api' => [],
            'web' => [],
            'admin' => []
        ];
    }

    /**
     * Boot the trait
     */
    public static function bootEagerLoadingOptimized()
    {
        static::addGlobalScope(new \App\Scopes\EagerLoadingScope());
    }

    /**
     * Set default eager loading relationships
     */
    public function setDefaultEagerLoad(array $relationships): self
    {
        if (property_exists($this, 'defaultEagerLoad')) {
            $this->defaultEagerLoad = $relationships;
        } else {
            $this->traitDefaultEagerLoad = $relationships;
        }
        return $this;
    }

    /**
     * Get default eager loading relationships
     */
    public function getDefaultEagerLoad(): array
    {
        return property_exists($this, 'defaultEagerLoad') ? $this->defaultEagerLoad : $this->traitDefaultEagerLoad;
    }

    /**
     * Scope untuk eager loading berdasarkan context
     */
    public function scopeWithContext($query, string $context = 'web')
    {
        $contextualEagerLoad = $this->getContextualEagerLoad();
        $relationships = $contextualEagerLoad[$context] ?? [];
        
        if (!empty($relationships)) {
            return $query->with($relationships);
        }
        
        return $query;
    }

    /**
     * Scope untuk eager loading dengan field select optimization
     */
    public function scopeWithOptimized($query, array $relations = [], array $fields = ['*'])
    {
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        if ($fields !== ['*']) {
            $query->select($fields);
        }
        
        return $query;
    }

    /**
     * Scope untuk eager loading dengan count relationships
     */
    public function scopeWithCounts($query, array $relations = [])
    {
        if (!empty($relations)) {
            return $query->withCount($relations);
        }
        
        return $query;
    }

    /**
     * Scope untuk eager loading dengan exists check
     */
    public function scopeWithExists($query, array $relations = [])
    {
        if (!empty($relations)) {
            return $query->withExists($relations);
        }
        
        return $query;
    }

    /**
     * Scope untuk lazy eager loading
     */
    public function scopeWithLazy($query, array $relations = [])
    {
        $collection = $query->get();
        
        if (!empty($relations) && $collection->isNotEmpty()) {
            $collection->load($relations);
        }
        
        return $collection;
    }

    /**
     * Scope untuk conditional eager loading
     */
    public function scopeWithWhen($query, bool $condition, array $relations = [])
    {
        if ($condition && !empty($relations)) {
            return $query->with($relations);
        }
        
        return $query;
    }

    /**
     * Scope untuk nested eager loading dengan batching
     */
    public function scopeWithBatched($query, array $relations = [], int $batchSize = 100)
    {
        $query->with($relations);
        
        // Implementasi batching untuk query besar
        return $query->chunk($batchSize, function ($models) use ($relations) {
            $models->load($relations);
        });
    }

    /**
     * Get optimized relationships untuk API response
     */
    public function getApiRelations(): array
    {
        $contextualEagerLoad = $this->getContextualEagerLoad();
        return $contextualEagerLoad['api'] ?? [];
    }

    /**
     * Get optimized relationships untuk Admin panel
     */
    public function getAdminRelations(): array
    {
        $contextualEagerLoad = $this->getContextualEagerLoad();
        return $contextualEagerLoad['admin'] ?? [];
    }

    /**
     * Get optimized relationships untuk Web view
     */
    public function getWebRelations(): array
    {
        $contextualEagerLoad = $this->getContextualEagerLoad();
        return $contextualEagerLoad['web'] ?? [];
    }
}
