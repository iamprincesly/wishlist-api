<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends BaseRepository
{
    /**
     * Create a new instance of UserRepository
     */
    public function __construct(?Model $resource = null)
    {
        parent::__construct($resource);
    }

    /**
     * Get searchable fields array
     */
    protected function searchableFields(): array
    {
        return [
            'name',
            'email',
        ];
    }

    /**
     * Get sortable fields array
     */
    protected function sortableFields(): array
    {
        return [
            'name',
            'created_at',
        ];
    }

    /**
     * Get needed fields array
     */
    protected function neededFields(): array
    {
        return [
            //
        ];
    }

    /**
     * Get include relations array
     */
    protected function includeRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * Configure the Model
     */
    protected function model(): string
    {
        return User::class;
    }

    /**
     * Get all records for the given model
     *
     * @param  bool  $withTrashed  = false,
     * @param  bool  $paginate  = true
     */
    public function all(Request $request, array $must_relations = [], bool $withTrashed = false, bool $paginate = true): Collection|Paginator
    {
        return $this->buildQuery(request: $request, additionalFilters: [], withTrashed: $withTrashed, paginate: $paginate);
    }
}
