<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Illuminate\Support\Facades\Hash;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LogoutTest extends DuskTestCase
{
    // Jika perlu migrasi ulang setiap test
    // use DatabaseMigrations;

    public function test_user_can_logout()
    {
        $user = User::updateOrCreate(
            ['email' => 'duskuser@example.com'], // cari user berdasarkan email
            [
                'name' => 'Dusk User', // buat atau update user dengan nama ini
                'password' => Hash::make('password'), // enkripsi password sebelum disimpan
            ]
        );

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login') // mengunjungi halaman login
                ->assertSee('Email') // memastikan form login muncul
                ->type('email', $user->email) // mengisi field email
                ->type('password', 'password') // mengisi field password
                ->press('LOG IN') // menekan tombol login
                ->pause(1000) // menunggu sebentar agar proses selesai
                ->assertPathIs('/dashboard') // memastikan berhasil redirect ke dashboard
                ->assertSee($user->name) // memastikan nama user muncul di halaman
                ->press($user->name) // menekan nama user untuk membuka dropdown
                ->clickLink('Log Out') // menekan tombol logout
                ->assertSeeLink('Log in'); // memastikan tombol login muncul setelah logout
        });
    }
}
