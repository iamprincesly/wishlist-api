<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Repositories\WishlistRepository;
use App\Http\Resources\WishlistCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WishlistController extends Controller
{
    public function __construct(private WishlistRepository $wishlistRepository) {}

    public function index(Request $request): ResourceCollection
    {
        $wishlist = $this->wishlistRepository->userWishlist($request, $request->user()->getKey());
        return WishlistCollection::make($wishlist);
    }

    public function addToWishlist(Request $request, Product $product)
    {
        if ($this->wishlistRepository->addToWishlist($request->user()->getKey(), $product->getKey())) {
            return api_success('Product added to wishlist', [], 201);
        }

        return api_failed('Product already in wishlist', 400);
    }

    public function removeFromWishlist(Request $request, Product $product)
    {
        if ($this->wishlistRepository->removeFromWishlist($request->user()->getKey(), $product->getKey())) {
            return api_success('Product removed from wishlist');
        }

        return api_failed('Product not found in wishlist', 404);
    }
}
