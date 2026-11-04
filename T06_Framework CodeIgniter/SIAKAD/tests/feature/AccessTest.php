<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

/** HTTP and role checks that do not require a database. */
final class AccessTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testLoginRendersCsrfForm(): void
    {
        $result = $this->get('login');
        $result->assertStatus(200);
        $result->assertSee('csrf_test_name');
        $result->assertSee('password');
    }

    public function testGuestCannotOpenAdmin(): void
    {
        $this->get('admin')->assertRedirectTo(site_url('login'));
    }

    public function testWrongRoleCannotOpenAdminUsers(): void
    {
        $this->withSession(['isLoggedIn' => true, 'role' => 'mahasiswa', 'user_id' => 1])
            ->get('admin/dosen')->assertRedirectTo(site_url('/'));
    }

    public function testLogoutDoesNotAcceptGet(): void
    {
        $this->expectException(\CodeIgniter\Exceptions\PageNotFoundException::class);
        $this->get('logout');
    }
}
