<?php

namespace Tests\Feature;

use Tests\TestCase;

class DomainTest extends TestCase
{
    public function test_domain_page_renders(): void
    {
        $this->withSession(['user_id' => 1, 'user_name' => 'Administrator'])
            ->get('/domain')
            ->assertOk()
            ->assertSee('Daftar Domain');
    }

    public function test_domain_detail_link_uses_real_domain_id(): void
    {
        $html = view('pages.domain.domain', [
            'domains' => [
                [
                    'id' => 42,
                    'domain' => 'example.com',
                    'application' => 'SIMA',
                    'status' => 'Aktif',
                    'pic' => 'Budi',
                    'ssl' => 'Aktif',
                    'last_activity' => '12 Sep 2026',
                ],
            ],
        ])->render();

        $this->assertStringContainsString('/domain/42', $html);
        $this->assertStringNotContainsString('/domain/1', $html);
    }
}
