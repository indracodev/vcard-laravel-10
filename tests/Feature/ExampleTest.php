<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_root_redirects_to_dashboard(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/dashboard');
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_dashboard_redirects_when_unauthenticated(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_dashboard_is_accessible_when_authenticated(): void
    {
        $response = $this->withSession(['login' => true, 'role' => 'hcs'])->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_users_management_is_forbidden_for_hcs_role(): void
    {
        $response = $this->withSession(['login' => true, 'role' => 'hcs'])->get('/users');

        $response->assertRedirect('/dashboard');
    }

    public function test_users_management_is_accessible_for_admin_role(): void
    {
        $response = $this->withSession(['login' => true, 'role' => 'admin'])->get('/users');

        $response->assertStatus(200);
    }

    public function test_session_expires_after_five_minutes_of_inactivity(): void
    {
        $response = $this->withSession([
            'login' => true,
            'role' => 'admin',
            'last_activity_at' => time() - 301,
        ])->get('/dashboard');

        $response->assertRedirect('/login');
    }
}
