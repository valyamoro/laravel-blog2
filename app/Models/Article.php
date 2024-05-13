<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
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
        'user_id',
        'title',
        'slug',
        'annotation',
        'content',
        'thumbnail',
        'views',
        'is_active',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

}
