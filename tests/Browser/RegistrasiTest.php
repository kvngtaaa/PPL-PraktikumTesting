<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Note;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DuskTestCase;

class RegistrasiTest extends DuskTestCase
{
    
    public function test_user_can_register()
    {
        User::where('email', 'duskuser@example.com')->delete(); // menghapus user dengan email 'duskuser@example.com' jika sudah ada

        $this->browse(function (Browser $browser) {
            $browser->visit('/register') // mengunjungi halaman registrasi
                ->type('name', 'Dusk User') // mengisi field "name" dengan "Dusk User"
                ->type('email', 'duskuser@example.com') // mengisi field "email" dengan "duskuser@example.com"
                ->type('password', 'password') // mengisi field "password" dengan "password"
                ->type('password_confirmation', 'password') // mengisi field "password_confirmation" dengan "password"
                ->press('REGISTER') // menekan tombol "REGISTER"
                ->assertPathIs('/dashboard') // memastikan bahwa setelah registrasi berhasil, user diarahkan ke halaman "/dashboard"
                ->press('Dusk User') // menekan dropdown atau nama user "Dusk User"
                ->clickLink('Log Out'); // mengklik link "Log Out" untuk keluar dari akun
        });
    }
}
