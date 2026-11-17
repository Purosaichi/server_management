<?php

namespace Tests\Feature;

use Tests\TestCase;

class DashboardDomainListTest extends TestCase
{
    public function test_dashboard_displays_domain_list_instead_of_expiry_reminders(): void
    {
        $html = view('pages.home.dashboard', [
            'stats' => null,
            'servers' => [],
            'applications' => [],
            'domains' => collect([
                [
                    'id' => 42,
                    'domain' => 'portal.example',
                    'application' => 'SIMA',
                    'status' => 'Aktif',
                    'pic' => 'Budi',
                    'ssl' => 'Valid',
                    'last_activity' => '13 January 2027',
                ],
            ]),
            'domainCount' => 1,
        ])->render();

        $this->assertStringContainsString('Daftar Domain', $html);
        $this->assertStringContainsString('portal.example', $html);
        $this->assertStringContainsString('SIMA', $html);
        $this->assertStringContainsString('13 January 2027', $html);
        $this->assertStringContainsString(route('domain.detail', 42), $html);
        $this->assertStringContainsString('class="link-all">Lihat Semua →</a>', $html);
        $this->assertStringNotContainsString('Reminder Kadaluarsa', $html);
        $this->assertStringNotContainsString('Sisa Hari', $html);
    }
}
