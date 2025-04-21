<?php

namespace Tests\Browser;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class EditNoteTest extends DuskTestCase
{
    public function test_user_can_edit_note()
    {
        // Buat atau update user
        User::updateOrCreate(
            ['email' => 'duskuser@example.com'], // mencari user berdasarkan email
            [
                'name' => 'Dusk User', // jika tidak ada, buat user baru dengan nama ini
                'password' => bcrypt('password'), // menyimpan password terenkripsi
            ]
        );
        // Pastikan passwordnya benar
        User::where('email', 'duskuser@example.com')->update([
            'password' => bcrypt('password'), // memperbarui password untuk memastikan login berhasil
        ]);
        $this->browse(function (Browser $browser) {
            // Visit login page and login
            $browser->visit('/login') // mengunjungi halaman login
                    ->type('email', 'duskuser@example.com') // mengisi kolom email
                    ->type('password', 'password') // mengisi kolom password
                    ->press('LOG IN'); // menekan tombol login

            // Debugging: Screenshot setelah login untuk memeriksa apakah login berhasil
            $browser->screenshot('login_screenshot'); // mengambil screenshot setelah login
            // Periksa apakah login berhasil dan diarahkan ke dashboard
            $browser->assertPathIs('/dashboard'); // memastikan berada di halaman dashboard
            // Klik link 'Notes' dan pastikan halaman yang terbuka benar
            $browser->clickLink('Notes') // mengklik menu "Notes"
                    ->assertPathIs('/notes'); // memastikan berada di halaman /notes
            // Klik link 'Edit' untuk mengedit catatan
            $browser->clickLink('Edit') // mengklik link "Edit" pada daftar catatan
                    ->assertPathBeginsWith('/edit-note-page/'); // memastikan path dimulai dengan /edit-note-page/
            // Pastikan form sudah siap, tunggu sedikit
            $browser->waitFor('input[name=title]'); // menunggu input field "title" muncul
            // Isi form dengan data baru
            $browser->type('title', 'Catatan Terupdate') // mengisi field title dengan teks baru
                    ->type('description', 'Isi catatan yang telah diperbarui'); // mengisi field description
            // Debugging: Screenshot sebelum klik tombol update
            $browser->screenshot('before_update'); // mengambil screenshot sebelum menekan tombol update
            // Klik tombol Update dan periksa apakah halaman kembali ke '/notes'
            $browser->pause(500) // Tambahkan delay jika perlu
                    ->press('UPDATE') // menekan tombol "UPDATE"
                    ->waitForLocation('/notes') // menunggu hingga diarahkan ke halaman /notes
                    ->assertPathIs('/notes') // memastikan berada di halaman /notes
                    ->assertSee('Note has been updated') // memastikan muncul notifikasi bahwa catatan telah diperbarui
                    ->press('Dusk User') // menekan nama user
                    ->clickLink('Log Out'); // mengklik link "Log Out" untuk keluar dari akun

        });
    }
}
