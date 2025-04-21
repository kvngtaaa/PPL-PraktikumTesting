<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Illuminate\Support\Facades\Hash;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends DuskTestCase
{
    // Jika perlu migrasi ulang setiap test
    // use DatabaseMigrations;

    public function test_user_can_login()
    {
        $user = User::updateOrCreate(
            ['email' => 'duskuser@example.com'], // mencari user berdasarkan email
            [
                'name' => 'Dusk User', // jika tidak ada, akan membuat user baru dengan nama ini
                'password' => Hash::make('password'), // menyimpan password terenkripsi
            ]
        );

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login') // mengunjungi halaman login
                ->assertSee('Email') // memastikan elemen dengan teks "Email" muncul 
                ->type('email', $user->email) // mengisi input "email" dengan email user
                ->type('password', 'password') // mengisi input "password" dengan "password"
                ->press('LOG IN') // menekan tombol "LOG IN"
                ->pause(1000) // memberi jeda 1 detik agar proses login selesai
                ->assertPathIs('/dashboard') // memastikan user diarahkan ke halaman dashboard
                ->assertSee($user->name) // memastikan nama user terlihat di halaman
                ->press($user->name) // menekan nama user
                ->clickLink('Log Out'); // mengklik link "Log Out" untuk keluar dari akun
        });
    }
}
