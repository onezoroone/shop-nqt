<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'excerpt',
        'description',
        'changelog',
        'thumbnail',
        'gallery',
        'price',
        'sale_price',
        'tech_stack',
        'demo_url',
        'source_url',
        'features',
        'tech_stack_csv',
        'features_csv',
        'is_featured',
        'download_count',
        'status',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $attributes = [
        'is_featured' => false,
        'download_count' => 0,
        'status' => 'published',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tech_stack' => 'array',
            'features' => 'array',
            'gallery' => 'array',
            'is_featured' => 'boolean',
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'published_at' => 'datetime',
        ];
    }

    public function getTechStackCsvAttribute(): string
    {
        return implode(', ', $this->tech_stack ?? []);
    }

    public function getThumbnailUrlAttribute(): string
    {
        if (! $this->thumbnail) {
            return asset('assets/images/placeholder.jpg'); // Hoặc ảnh mặc định
        }

        if (str_starts_with($this->thumbnail, 'http')) {
            return $this->thumbnail;
        }

        if (str_starts_with($this->thumbnail, '/')) {
            return url($this->thumbnail);
        }

        return asset('storage/'.$this->thumbnail);
    }

    public function setTechStackCsvAttribute($value): void
    {
        $this->attributes['tech_stack'] = json_encode(array_filter(array_map('trim', explode(',', $value))));
    }

    public function getFeaturesCsvAttribute(): string
    {
        return implode(', ', $this->features ?? []);
    }

    public function setFeaturesCsvAttribute($value): void
    {
        $this->attributes['features'] = json_encode(array_filter(array_map('trim', explode(',', $value))));
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsToMany<Category, $this>
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * @return HasMany<ProductImage, $this>
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('published_at')->orderByDesc('id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isOnSale(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    public function getCurrentPriceAttribute(): string
    {
        return $this->isOnSale() ? $this->sale_price : $this->price;
    }

    public function getDiscountPercentAttribute(): int
    {
        if (! $this->isOnSale()) {
            return 0;
        }

        return (int) round((1 - $this->sale_price / $this->price) * 100);
    }
}
