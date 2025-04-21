<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class DeleteNoteTest extends DuskTestCase
{
    /**
     * A Dusk test for deleting a note.
     */
    public function testExample(): void
    {
         // Buat atau update user
         User::updateOrCreate(
            ['email' => 'duskuser@example.com'], // mencari user berdasarkan email
            [
                'name' => 'Dusk User', // jika belum ada, buat user baru dengan nama ini
                'password' => bcrypt('password'), // mengenkripsi password
            ]
        );

        // Pastikan passwordnya benar
        User::where('email', 'duskuser@example.com')->update([
            'password' => bcrypt('password'), // memperbarui password agar sesuai
        ]);

        $this->browse(function (Browser $browser) {
             // Delete Note
             $browser
            ->visit('/login') // mengunjungi halaman login
            ->type('email', 'duskuser@example.com') // mengisi email pada form login
            ->type('password', 'password') // mengisi password pada form login
            ->press('LOG IN') // menekan tombol login
            ->assertPathIs('/dashboard') // memastikan sudah berhasil login dan diarahkan ke dashboard
            ->clickLink('Notes') // menekan link untuk menuju halaman Notes
            ->pause(1000) // menunggu proses loading halaman Notes
            ->press('Delete') // menekan tombol delete untuk menghapus catatan
            ->assertPathIs('/notes') // memastikan setelah delete tetap berada di halaman Notes
            ->assertSee('Note has been deleted') // memastikan muncul notifikasi bahwa catatan berhasil dihapus
            ->press('Dusk User') // menekan nama user di bagian header
            ->clickLink('Log Out') // menekan tombol untuk logout dari aplikasi
            ->assertPathIs('/'); // memastikan diarahkan ke halaman utama setelah logout
        });
    }
}
