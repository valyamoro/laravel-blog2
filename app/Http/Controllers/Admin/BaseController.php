<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

abstract class BaseController extends Controller
{
    protected string $defaultPerPage = 'pagination_20';
}
