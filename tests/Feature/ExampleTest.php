<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_about_page_displays_the_story_photo(): void
    {
        $this->get(route('about'))
            ->assertOk()
            ->assertSee('images/about-story-dining.png')
            ->assertSee('alt="Hidangan yang disajikan di meja makan"', false)
            ->assertSee('Sejak 2024');
    }
}
