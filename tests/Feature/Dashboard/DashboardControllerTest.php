<?php

namespace Dashboard;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use WithoutMiddleware;

    public function testGetViewDashboardIndex(): void
    {
        $title = 'Панель управления';

        $response = $this->get(route('admin.dashboard'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.dashboards.index');
        $response->assertViewHas([
            'title' => $title,
        ]);
    }

}
