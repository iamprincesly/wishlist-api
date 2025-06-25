<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ProductRepository;
use App\Http\Resources\ProductCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductController extends Controller
{
    public function __construct(private ProductRepository $productRepository) {}

    public function index(Request $request): ResourceCollection
    {
        $products = $this->productRepository->all($request);
        return ProductCollection::make($products);
    }
}
