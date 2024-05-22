<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class AdminUser
 *
 * @property int $id ID
 * @property string $username Имя
 * @property string $email Почта
 * @property string $password Пароль
 * @property bool $is_banned Бан
 * @property \Illuminate\Support\Carbon|null $created_at Дата создания
 * @property \Illuminate\Support\Carbon|null $updated_at Дата обновления
 *
 * @mixin Builder
 * @package App\Models
 */
class AdminUser extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'username',
        'email',
        'password',
        'is_banned',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_banned' => 'boolean',
        'password' => 'hashed',
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

}
