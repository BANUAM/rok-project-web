<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class InertiaVueStackTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_home_page_renders_inertia_vue_component(): void
    {
        $response = $this->get('/?inertia=1');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Home')
            ->has('divisions')
            ->has('achievements')
        );
    }

    public function test_teams_page_renders_inertia_vue_component(): void
    {
        $response = $this->get('/teams?inertia=1');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Teams')
            ->has('founders')
            ->has('divisions')
        );
    }

    public function test_achievements_page_renders_inertia_vue_component(): void
    {
        $response = $this->get('/achievements?inertia=1');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Achievements')
            ->has('achievements')
        );
    }

    public function test_learning_development_renders_inertia_vue_component(): void
    {
        $response = $this->get('/learning-development?inertia=1');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('LearningDevelopment')
            ->has('modules')
        );
    }

    public function test_bracket_renders_inertia_vue_component(): void
    {
        $response = $this->get('/bracket?inertia=1');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Bracket')
            ->has('activeTournament')
        );
    }

    public function test_membership_renders_inertia_vue_component(): void
    {
        $response = $this->get('/membership?inertia=1');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Membership/Index')
            ->has('plans')
        );
    }

    public function test_login_page_renders_inertia_vue_component(): void
    {
        $response = $this->get('/login?inertia=1');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Auth/Login')
        );
    }

    public function test_inertia_json_protocol_response(): void
    {
        $response = $this->withHeaders(['X-Inertia' => 'true'])->get('/');

        $response->assertStatus(200);
        $response->assertHeader('X-Inertia', 'true');
        $response->assertJsonPath('component', 'Home');
    }
}
