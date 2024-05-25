<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Article
 *
 * @property int $id ID
 * @property int $category_id ID категории
 * @property int $user_id ID пользователя
 * @property string $title Название
 * @property string $slug ЧПУ
 * @property string $annotation Описание
 * @property string $content Содержимое статьи
 * @property string $thumbnail Изображение
 * @property int $view Просмотры
 * @property bool $is_active Активность
 * @property \Illuminate\Support\Carbon|null $created_at Дата создания
 * @property \Illuminate\Support\Carbon|null $updated_at Дата обновления
 * @mixin Builder
 */
class Article extends BaseModel
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'category_id',
        'admin_user_id',
        'title',
        'slug',
        'annotation',
        'content',
        'thumbnail',
        'views',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    public function adminUser(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($article) {
            $article->tags()->detach();
        });

        static::updated(function ($article) {
            $article->tags()->sync(request('tags'));
        });

        static::created(function ($article) {
            $article->tags()->attach(request()->input('tags'));
        });
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

}
