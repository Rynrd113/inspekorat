<?php

namespace App\Traits;

trait HasEagerLoading
{
    /**
     * Default relationships yang akan di-load secara eager
     */
    protected $defaultEagerLoad = [];

    /**
     * Boot the trait
     */
    public static function bootHasEagerLoading()
    {
        static::addGlobalScope(new \App\Scopes\EagerLoadingScope());
    }

    /**
     * Set default eager loading relationships
     */
    public function setDefaultEagerLoad(array $relationships): self
    {
        $this->defaultEagerLoad = $relationships;
        return $this;
    }

    /**
     * Get default eager loading relationships
     */
    public function getDefaultEagerLoad(): array
    {
        return $this->defaultEagerLoad;
    }

    /**
     * Scope untuk eager loading dengan relationships default
     */
    public function scopeWithDefault($query)
    {
        if (!empty($this->defaultEagerLoad)) {
            return $query->with($this->defaultEagerLoad);
        }
        return $query;
    }

    /**
     * Scope untuk eager loading dengan relationships tertentu
     */
    public function scopeWithRelations($query, array $relations)
    {
        return $query->with($relations);
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
}
