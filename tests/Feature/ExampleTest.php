<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_redirects_guests_to_the_login_page(): void
    {
        $this->get('/')->assertRedirect(route('login'));

        $this->get(route('login'))->assertOk();
    }
}
