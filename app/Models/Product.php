<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable (fillable for create and update operations).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    /**
     * The attributes that should be cast to native types (automatic type conversion).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'integer',
    ];

    /**
     * Product can belong to multiple users' wishlists.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wishlistedBy()
    {
        return $this->hasMany(Wishlist::class);
    }
}
