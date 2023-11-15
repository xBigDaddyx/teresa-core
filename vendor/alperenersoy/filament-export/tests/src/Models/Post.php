<?php

namespace AlperenErsoy\FilamentExport\Tests\Models;

use AlperenErsoy\FilamentExport\Tests\Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    protected $casts = [
        'tags' => 'array',
    ];

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function newFactory()
    {
        return PostFactory::new();
    }
}
