<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseModel
 *
 * @mixing Builder
 *
 */
class BaseModel extends Model
{
    use HasFactory;

}
