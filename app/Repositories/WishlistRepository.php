<?php

namespace App\Repositories;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\Paginator;

class WishlistRepository extends BaseRepository
{
    /**
     * Create a new instance of WishlistRepository
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
            'products.name',
            'products.description',
            'products.price',
        ];
    }

    /**
     * Get sortable fields array
     */
    protected function sortableFields(): array
    {
        return [
            'products.name',
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
            'product',
            'user',
        ];
    }

    /**
     * Configure the Model
     */
    protected function model(): string
    {
        return Wishlist::class;
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

    public function userWishlist(Request $request, int $userId): Collection|Paginator
    {
        return $this->buildQuery(
             $request, [], false,
             function ($query) use ($userId) {
                $query->with('product')->where('user_id', $userId);
                return $query;
            }
        );
    }

    public function addToWishlist(int $userId, int $productId): Wishlist
    {
        return Wishlist::firstOrCreate([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);
    }

    public function removeFromWishlist(int $userId, int $productId): bool
    {
       return Wishlist::where('user_id', $userId)->where('product_id', $productId)->delete();
    }
}
