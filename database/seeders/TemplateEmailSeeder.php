<?php

namespace Database\Seeders;

use App\Models\TemplateEmail;
use Illuminate\Database\Seeder;

class TemplateEmailSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama'      => 'Pengumuman',
                'deskripsi' => 'Pengumuman resmi perusahaan',
                'subject'   => '[PENGUMUMAN] Informasi Penting',
                'body'      => "Kepada seluruh karyawan,\n\nDalam rangka pelaksanaan kegiatan perusahaan, kami sampaikan informasi penting berikut ini:\n\n• Detail informasi: ...\n• Waktu: ...\n• Tempat: ...\n\nMohon perhatian dan kerja samanya.\n\nHormat kami,\nManajemen",
                'status'    => 'active',
            ],
            [
                'nama'      => 'Undangan',
                'deskripsi' => 'Undangan rapat & kegiatan',
                'subject'   => '[UNDANGAN] Rapat Koordinasi',
                'body'      => "Kami mengundang Bapak/Ibu untuk hadir dalam rapat koordinasi yang akan dilaksanakan pada:\n\nHari/Tanggal : ...\nWaktu        : ...\nTempat       : ...\nAgenda       : ...\n\nMohon konfirmasi kehadiran.\n\nHormat kami,\nManajemen",
                'status'    => 'active',
            ],
            [
                'nama'      => 'Informasi Perusahaan',
                'deskripsi' => 'Informasi operasional & internal',
                'subject'   => '[INFORMASI] Update Operasional',
                'body'      => "Berikut informasi operasional terkait kegiatan perusahaan yang perlu diketahui oleh seluruh karyawan:\n\n• Poin informasi 1\n• Poin informasi 2\n• Poin informasi 3\n\nTerima kasih atas perhatiannya.\n\nHormat kami,\nManajemen",
                'status'    => 'active',
            ],
            [
                'nama'      => 'Kegiatan / Event',
                'deskripsi' => 'Agustusan, gathering, event',
                'subject'   => '[EVENT] Kegiatan Perusahaan',
                'body'      => "Dalam rangka memperingati dan mempererat kebersamaan, perusahaan akan mengadakan kegiatan yang diikuti seluruh karyawan.\n\nDetail kegiatan:\n\nHari/Tanggal : ...\nWaktu        : ...\nTempat       : ...\nAcara        : ...\n\nMari berpartisipasi dan meriahkan acara ini!\n\nHormat kami,\nPanitia Kegiatan",
                'status'    => 'active',
            ],
        ];

        foreach ($data as $d) {
            TemplateEmail::updateOrCreate(
                ['nama' => $d['nama']],
                $d
            );
        }

        $this->command->info('✓ TemplateEmailSeeder: ' . count($data) . ' template berhasil di-seed.');
    }
}