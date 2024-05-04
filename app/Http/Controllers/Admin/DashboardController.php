<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;

class DashboardController extends BaseController
{
    public function index(): View
    {
        $title = 'Панель управления';

        return view('admin.dashboards.index', [
            'title' => $title
        ]);
    }

}
