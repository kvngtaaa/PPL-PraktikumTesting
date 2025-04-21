<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateNoteTest extends DuskTestCase
{
    public function test_user_can_create_note()
    {
        // Buat user test
        $user = User::updateOrCreate(
            ['email' => 'duskuser@example.com'], // mencari user berdasarkan email
            [
                'name' => 'Dusk User', // jika tidak ada, buat user baru dengan nama ini
                'password' => Hash::make('password'), // menyimpan password yang terenkripsi
            ]
        );

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login') // mengunjungi halaman login
                ->type('email', $user->email) // mengisi kolom email dengan email user
                ->type('password', 'password') // mengisi kolom password dengan "password"
                ->press('LOG IN') // menekan tombol login
                ->pause(1000) // menunggu 1 detik untuk memastikan proses login selesai
                ->assertPathIs('/dashboard') // memastikan halaman yang dituju adalah dashboard

                ->clickLink('Notes') // mengklik link "Notes" di navigasi
                ->pause(1000) // menunggu 1 detik agar halaman termuat
                ->assertPathIs('/notes') // memastikan user berada di halaman /notes

                ->clickLink('Create Note') // mengklik link untuk membuat catatan baru
                ->pause(1000) // menunggu 1 detik agar halaman form create termuat
                ->assertPathIs('/create-note') // memastikan berada di halaman /create-note

                ->type('title', 'Catatan Dusk') // mengisi kolom judul dengan "Catatan Dusk"
                ->type('description', 'Ini catatan test untuk Laravel Dusk.') // mengisi kolom deskripsi
                ->press('CREATE') // menekan tombol "CREATE" untuk menyimpan catatan
                ->pause(1000) // menunggu 1 detik agar proses selesai
                ->assertSee('new note has been created') // memastikan pesan sukses muncul

                ->press($user->name) // menekan nama user
                ->clickLink('Log Out'); // mengklik link "Log Out" untuk keluar dari akun

        });
    }
}
