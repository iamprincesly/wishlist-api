<?php

namespace App\Repositories;

use Exception;
use Illuminate\Container\Container as Application;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Application
     */
    protected $app;

    /**
     * The resource object
     */
    protected ?Model $resource;

    /**
     * Create a new class instance.
     */
    public function __construct(?Model $resource = null)
    {
        if (! is_null($resource) && $resource->exists === false) {
            throw new \Exception("Resource {$resource} must be existing record in database.");
        }

        $this->resource = $resource;

        $this->app = app();

        $this->makeModel();
    }

    /**
     * Make a new repository
     *
     *
     * @return $this
     */
    public static function make(Model $resource): static
    {
        return app()->make(static::class, [
            'resource' => $resource,
        ]);
    }

    /**
     * Get searchable fields array
     */
    abstract protected function searchableFields(): array;

    /**
     * Get sortable fields array
     */
    abstract protected function sortableFields(): array;

    /**
     * Get needed fields array
     */
    abstract protected function neededFields(): array;

    /**
     * Get include relations array
     */
    abstract protected function includeRelations(): array;

    /**
     * Configure the Model
     */
    abstract protected function model(): string;

    /**
     * Get all records for the given model
     *
     * @param  bool  $withTrashed  = false,
     * @param  bool  $paginate  = true
     */
    abstract public function all(Request $request, array $must_relations = [], bool $withTrashed = false, bool $paginate = true): Collection|Paginator;

    /**
     * Make Model instance
     *
     * @return Model
     *
     * @throws \Exception
     */
    private function makeModel()
    {
        $model = $this->app->make($this->model());

        if (! $model instanceof Model) {
            throw new Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * Advannce query builder
     *
     * @param  bool  $first  = false
     * @param  bool  $paginate  = true
     *
     * @throws \InvalidArgumentException if the callback does not return a \Illuminate\Database\Eloquent\Builder.
     */
    protected function buildQuery(Request $request, array $additionalFilters, bool $withTrashed, ?callable $additionalQuery = null, bool $first = false, bool $paginate = true): Collection|Paginator|Model|null
    {
        $query = $this->model->newQuery();

        if (is_callable($additionalQuery)) {

            $query = $additionalQuery($query);

            if (! $query instanceof Builder) {
                throw new \InvalidArgumentException('The callable must return an instance of \Illuminate\Database\Eloquent\Builder object');
            }
        }

        ensureArrayOfType($additionalFilters, AllowedFilter::class);

        foreach ($this->searchableFields() as $field) {
            $additionalFilters[] = AllowedFilter::partial($field);
        }

        $sorts = array_map(fn ($sort) => AllowedSort::field($sort), $this->sortableFields());

        $relations = array_map(fn ($relation) => AllowedInclude::relationship($relation), $this->includeRelations());

        $builder = QueryBuilder::for($query, $request)->allowedFilters($additionalFilters);

        if (! empty($sorts)) {
            $builder->allowedSorts($sorts);
        }

        if (empty($this->neededFields())) {
            $builder->allowedFields($this->neededFields());
        }

        if (! empty($relations)) {
            $builder->allowedIncludes($relations);
        }

        if ($withTrashed === true) {
            $builder->withTrashed();
        }

        if ($first === true) {
            return $builder->first();
        }

        if ($paginate === true) {
            return $builder->paginate($request->input('perpage', 10))->appends($request->query());
        }

        return $builder->get();
    }

    /**
     * Create model record
     */
    public function create(array $input): Model
    {
        $model = $this->model->newInstance($input);

        $model->save();

        return $model;
    }

    /**
     * Find model record for given id
     *
     *
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function find(string $id, array $columns = ['*'])
    {
        return $this->model->newQuery()->find($id, $columns);
    }

    /**
     * Find model record by a given field
     *
     * @param  mixed  $value
     */
    public function findBy(string $field, $value, array $columns = ['*']): ?Model
    {
        return $this->model->newQuery()->select($columns)->where($field, $value)->first();
    }

    /**
     * Update model record for given id
     */
    public function update(string $id, array $input): Model|bool
    {
        $model = $this->find($id);

        if (! $model) {
            return false;
        }

        $model->fill(Arr::whereNotNull($input));

        $model->save();

        return $model;
    }

    /**
     * Delete a model record
     */
    public function delete(string $id): bool
    {
        $model = $this->find($id);

        if (! $model) {
            return false;
        }

        return $model->delete();
    }

    /**
     * Update one resource
     */
    public function updateResource(array $input): Model
    {
        if (is_null($this->resource)) {
            throw new \Exception('Please pass a resource to update');
        }

        $this->resource->update(Arr::whereNotNull($input));

        return $this->resource;
    }

    /**
     * Delete one resource
     */
    public function deleteResource(): bool
    {
        if (is_null($this->resource)) {
            throw new \Exception('Please pass resource to delete');
        }

        return $this->resource->delete();
    }
}
