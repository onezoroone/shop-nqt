<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'excerpt',
        'description',
        'thumbnail',
        'tech_stack',
        'demo_url',
        'source_url',
        'is_featured',
        'sort_order',
        'status',
        'published_at',
    ];

    protected $attributes = [
        'is_featured' => false,
        'sort_order' => 0,
        'status' => 'published',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tech_stack' => 'array',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function getTechStackCsvAttribute()
    {
        return implode(', ', $this->tech_stack ?? []);
    }

    public function setTechStackCsvAttribute($value)
    {
        $this->attributes['tech_stack'] = json_encode(array_filter(array_map('trim', explode(',', $value))));
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
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
}
