<?php
// database/seeders/OtpSettingsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\System_setting;

class OtpSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'whatsapp_otp_enabled',
                'value' => '0'
            ],
            [
                'key' => 'onesender_api_url',
                'value' => 'https://sender.api-wa.my.id/api/v1/messages'
            ],
            [
                'key' => 'onesender_api_key',
                'value' => ''
            ],
            [
                'key' => 'otp_login_message',
                'value' => "Kode Verifikasi Login {app_name}\n\nKode OTP Anda: *{otp_code}*\n\nMasukkan kode ini untuk login ke akun Anda.\nKode berlaku selama 5 menit.\n\nJangan bagikan kode ini kepada siapapun!"
            ],
            [
                'key' => 'otp_register_message', 
                'value' => "Kode Verifikasi Registrasi {app_name}\n\nKode OTP Anda: *{otp_code}*\n\nMasukkan kode ini untuk menyelesaikan proses registrasi.\nKode berlaku selama 5 menit.\n\nJangan bagikan kode ini kepada siapapun!"
            ],
            [
                'key' => 'otp_welcome_message',
                'value' => "Selamat datang di {app_name}!\n\nHalo {name}!\n\nTerima kasih telah bergabung dengan kami. Akun Anda telah berhasil didaftarkan.\n\nSelamat menggunakan layanan kami!"
            ]
        ];

        foreach ($settings as $setting) {
            System_setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }

        $this->command->info('WhatsApp OTP settings seeded successfully.');
    }
}

// Untuk menjalankan seeder:
// php artisan db:seed --class=OtpSettingsSeeder

// Atau tambahkan ke DatabaseSeeder.php:
/*
public function run()
{
    $this->call([
        // ... seeder lainnya
        OtpSettingsSeeder::class,
    ]);
}
*/