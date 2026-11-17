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

    public function test_public_navigation_has_an_accessible_mobile_menu_toggle(): void
    {
        foreach ([route('home'), route('about'), route('contact')] as $url) {
            $this->get($url)
                ->assertOk()
                ->assertSee('data-mobile-nav-toggle', false)
                ->assertSee('aria-controls="primary-navigation"', false)
                ->assertSee('id="primary-navigation"', false);
        }
    }

    public function test_about_page_displays_the_story_photo(): void
    {
        $this->get(route('about'))
            ->assertOk()
            ->assertSee('class="about-hero"', false)
            ->assertSee('images/about-story-dining.png')
            ->assertSee('alt="Hidangan yang disajikan di meja makan"', false)
            ->assertSee('Sejak 2024')
            ->assertDontSee('Gabung Komunitas')
            ->assertDontSee('Siap memasak bersama kami?')
            ->assertDontSee('Daftar Sekarang');

        $this->assertFileExists(public_path('images/about-hero-dining.png'));
    }
}
