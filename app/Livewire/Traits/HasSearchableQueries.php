<?php

namespace App\Livewire\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasSearchableQueries
{
    /**
     * Search query parameter
     */
    public string $search = '';

    /**
     * Apply search filter to a query across multiple fields
     *
     * @param Builder $query The query builder instance
     * @param array $fields Array of field names to search (e.g., ['name', 'email', 'user.name'])
     * @return Builder
     */
    protected function applySearch(Builder $query, array $fields): Builder
    {
        if (empty($this->search)) {
            return $query;
        }

        return $query->where(function ($q) use ($fields) {
            foreach ($fields as $field) {
                if (str_contains($field, '.')) {
                    // Handle relationship fields (e.g., 'user.name')
                    $this->applyRelationshipSearch($q, $field);
                } else {
                    // Handle direct model fields
                    $q->orWhere($field, 'like', '%' . $this->search . '%');
                }
            }
        });
    }

    /**
     * Apply search on a relationship field
     *
     * @param Builder $query
     * @param string $field Relationship.field format (e.g., 'user.name')
     * @return void
     */
    protected function applyRelationshipSearch(Builder $query, string $field): void
    {
        [$relation, $relationField] = explode('.', $field, 2);

        $query->orWhereHas($relation, function ($relationQuery) use ($relationField) {
            if (str_contains($relationField, '.')) {
                // Nested relationships (e.g., 'user.profile.name')
                $this->applyRelationshipSearch($relationQuery, $relationField);
            } else {
                $relationQuery->where($relationField, 'like', '%' . $this->search . '%');
            }
        });
    }

    /**
     * Reset search when updating the search field
     * This automatically resets pagination
     *
     * @return void
     */
    public function updatingSearch(): void
    {
        // Reset pagination when search changes
        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }
}
