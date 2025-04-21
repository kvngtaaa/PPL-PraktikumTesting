<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    /**
     * Test fitur login pengguna
     */
    public function testLogin(): void
    {
        $this->browse(function (Browser $browser) {
            // Mengunjungi halaman login
            $browser->visit('http://127.0.0.1:8000/login') //mengunjungi halaman login
                    ->assertSee('LOG IN') //memastikan bahwa teks "Login" ada di halaman
                    ->type('email', 'test@example.com') //mengisi kolom 'email' dengan 'test@example.com'
                    ->type('password', 'password') //mengisi kolom 'password' dengan 'password'
                    ->press('LOG IN') //menekan tombol 'Login'
                    ->assertPathIs('/dashboard'); //memastikan setelah login user diarahkan ke halaman '/dashboard'
        });
    }
}
