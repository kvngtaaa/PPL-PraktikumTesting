<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegistrasiTest extends DuskTestCase
{
    /**
     * Test fitur registrasi pengguna
     */
    public function testRegistrasi(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://127.0.0.1:8000/register') //mengunjungi halaman registrasi
                    ->assertSee('REGISTER') //memastikan bahwa teks "Register" ada di halaman
                    // Mengisi form registrasi
                    ->type('name', 'Test') //mengisi kolom 'name' dengan 'Test'
                    ->type('email', 'test@example.com') //mengisi kolom 'email' dengan 'test@example.com'
                    ->type('password', 'password') //mengisi kolom 'password' dengan 'password'
                    ->type('password_confirmation', 'password') //mengisi kolom 'password_confirmation' dengan 'password'
                    ->press('REGISTER') //menekan tombol 'Register'
                    ->assertPathIs('/dashboard'); //memastikan setelah registrasi user diarahkan ke halaman '/dashboard'

        });
    }
}
