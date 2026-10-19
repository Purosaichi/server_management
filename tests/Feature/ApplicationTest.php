<?php

namespace Tests\Feature;

use Illuminate\Support\MessageBag;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    public function test_create_application_form_lists_available_domains(): void
    {
        $html = view('pages.application.create', [
            'errors' => new MessageBag(),
            'servers' => collect(),
            'licenses' => collect(),
            'domains' => collect([(object) [
                'id_domain' => 42,
                'nama_domain' => 'example.com',
            ]]),
        ])->render();

        $this->assertStringContainsString('name="id_domain"', $html);
        $this->assertStringContainsString('<option value="42"', $html);
        $this->assertStringContainsString('example.com', $html);
        $this->assertStringNotContainsString('name="nama_domain"', $html);
    }
}