<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApplicationTest extends TestCase
{
    public function test_create_application_form_lists_existing_domains(): void
    {
        $html = view('pages.application.create', [
            'servers' => collect(),
            'licenses' => collect(),
            'domains' => collect([(object) [
                'id_domain' => 42,
                'nama_domain' => 'example.com',
                'nama_aplikasi' => 'Aplikasi Lama',
            ]]),
        ])->render();

        $this->assertStringContainsString('name="id_domain"', $html);
        $this->assertStringContainsString('<option value="42"', $html);
        $this->assertStringContainsString('example.com (saat ini: Aplikasi Lama)', $html);
        $this->assertStringNotContainsString('name="nama_domain"', $html);
    }
}