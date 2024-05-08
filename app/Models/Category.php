<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Category
 *
 * @property int $id ID
 * @property int $parent_id ID родителя
 * @property string $slug ЧПУ
 * @property string $name Название
 * @property string $content Описание
 * @property string $thumbnail Изображение
 * @property int $view Просмотры
 * @property bool $is_active Активность
 * @property \Illuminate\Support\Carbon|null $created_at Дата создания
 * @property \Illuminate\Support\Carbon|null $updated_at Дата обновления
 * @mixin Builder
 */
class Category extends BaseModel
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'parent_id',
        'slug',
        'name',
        'content',
        'thumbnail',
        'view',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

}
