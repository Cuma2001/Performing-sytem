<?php

namespace Tests\Unit;

use App\Http\Controllers\DashboardController;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    public function test_dashboard_key_resolution_for_supported_roles(): void
    {
        $controller = new DashboardController();

        $this->assertSame('ceo-hr', $controller->resolveDashboardKey('CEO/HR'));
        $this->assertSame('ceo-hr', $controller->resolveDashboardKey('HR'));
        $this->assertSame('supervisor', $controller->resolveDashboardKey('Supervisor'));
        $this->assertSame('salesperson', $controller->resolveDashboardKey('Salesperson'));
    }
}
