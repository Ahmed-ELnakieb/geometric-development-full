<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProjectUnitType extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'size_min',
        'size_max',
        'description',
        'image_id',
        'display_order',
    ];

    protected $casts = [
        'size_min' => 'decimal:2',
        'size_max' => 'decimal:2',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}
