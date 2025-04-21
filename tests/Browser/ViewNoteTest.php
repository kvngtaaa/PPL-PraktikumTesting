<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ViewNoteTest extends DuskTestCase
{
    public function test_user_can_create_note()
    {
        // Pastikan user tersedia (tidak insert ulang)
        User::updateOrCreate(
            ['email' => 'duskuser@example.com'],
            [
                'name' => 'Dusk User',
                'password' => bcrypt('password'),
            ]
        );

        $this->browse(function (Browser $browser) {
            // Login terlebih dahulu
            $browser->visit('/login') // mengunjungi halaman login
                    ->waitFor('input[name=email]') // menunggu elemen email muncul
                    ->type('email', 'duskuser@example.com') // mengisi kolom email
                    ->type('password', 'password') // mengisi kolom password
                    ->press('LOG IN') // menekan tombol "LOG IN"
                    ->pause(1000) // beri waktu render halaman
                    ->screenshot('after-login') // ambil screenshot setelah login
                    ->assertPathIs('/dashboard') // memastikan berhasil login ke halaman dashboard

                    // Arahkan ke halaman Notes
                    ->clickLink('Notes') // mengklik link "Notes"
                    ->pause(1000) // beri waktu jika ada animasi atau delay
                    ->assertPathIs('/notes') // memastikan diarahkan ke halaman /notes
                    ->assertSee('Author') // memastikan elemen "Author" tampil

                    // Logout dari sistem
                    ->press('Dusk User') // menekan nama user untuk buka dropdown
                    ->clickLink('Log Out'); // menekan tombol logout
        });
    }
}
