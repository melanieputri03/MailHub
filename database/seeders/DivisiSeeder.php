<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Illuminate\Database\Seeder;

class DivisiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama'      => 'HR',
                'deskripsi' => 'Human Resource / Sumber Daya Manusia',
                'status'    => 'active',
            ],
            [
                'nama'      => 'Finance',
                'deskripsi' => 'Keuangan & Akuntansi',
                'status'    => 'active',
            ],
            [
                'nama'      => 'Customer Services',
                'deskripsi' => 'Layanan Pelanggan',
                'status'    => 'active',
            ],
            [
                'nama'      => 'Comercial',
                'deskripsi' => 'Divisi Komersial / Pemasaran',
                'status'    => 'active',
            ],
            [
                'nama'      => 'Legal',
                'deskripsi' => 'Legal & Kepatuhan',
                'status'    => 'active',
            ],
            [
                'nama'      => 'GMO',
                'deskripsi' => 'General Management Office',
                'status'    => 'active',
            ],
            [
                'nama'      => 'Procurement',
                'deskripsi' => 'Pengadaan Barang & Jasa',
                'status'    => 'active',
            ],
            [
                'nama'      => 'IMT',
                'deskripsi' => 'Information Management & Technology',
                'status'    => 'active',
            ],
            [
                'nama'      => 'Maintenance Building',
                'deskripsi' => 'Pemeliharaan Gedung',
                'status'    => 'active',
            ],
            [
                'nama'      => 'UMT',
                'deskripsi' => 'Utility Management Team',
                'status'    => 'active',
            ],
            [
                'nama'      => 'PH Power House',
                'deskripsi' => 'Power House',
                'status'    => 'active',
            ],
            [
                'nama'      => 'OHS',
                'deskripsi' => 'Occupational Health & Safety',
                'status'    => 'active',
            ],
            [
                'nama'      => 'Security',
                'deskripsi' => 'Keamanan Kawasan',
                'status'    => 'active',
            ],
        ];

        foreach ($data as $d) {
            Divisi::updateOrCreate(
                ['nama' => $d['nama']],
                $d
            );
        }

        $this->command->info('✓ DivisiSeeder: ' . count($data) . ' divisi berhasil di-seed.');
    }
}