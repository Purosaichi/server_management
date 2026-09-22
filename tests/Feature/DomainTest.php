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
}
