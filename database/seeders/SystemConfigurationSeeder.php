<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if data already exists
        if (DB::table('system_configurations')->count() > 0) {
            return;
        }

        $configurations = [
            [
                'key' => 'site_name',
                'value' => 'Portal Inspektorat Papua Tengah',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Nama website/portal',
                'is_public' => true,
                'updated_by' => 1,
            ],
            [
                'key' => 'site_description',
                'value' => 'Portal resmi Inspektorat Daerah Provinsi Papua Tengah untuk pelayanan publik dan transparansi pemerintahan',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Deskripsi website/portal',
                'is_public' => true,
                'updated_by' => 1,
            ],
            [
                'key' => 'contact_email',
                'value' => 'inspektorat@papuatengah.go.id',
                'type' => 'string',
                'group' => 'contact',
                'description' => 'Email kontak utama',
                'is_public' => true,
                'updated_by' => 1,
            ],
            [
                'key' => 'contact_phone',
                'value' => '(0984) 21567',
                'type' => 'string',
                'group' => 'contact',
                'description' => 'Nomor telepon kontak',
                'is_public' => true,
                'updated_by' => 1,
            ],
            [
                'key' => 'contact_address',
                'value' => 'Jl. Trikora No. 45, Nabire, Papua Tengah 98816',
                'type' => 'text',
                'group' => 'contact',
                'description' => 'Alamat kantor',
                'is_public' => true,
                'updated_by' => 1,
            ],
            [
                'key' => 'max_file_upload_size',
                'value' => '10',
                'type' => 'integer',
                'group' => 'file_management',
                'description' => 'Maksimal ukuran file upload dalam MB',
                'is_public' => false,
                'updated_by' => 1,
            ],
            [
                'key' => 'allowed_file_types',
                'value' => 'pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif',
                'type' => 'string',
                'group' => 'file_management',
                'description' => 'Tipe file yang diizinkan untuk upload',
                'is_public' => false,
                'updated_by' => 1,
            ],
            [
                'key' => 'wbs_auto_response',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'wbs',
                'description' => 'Aktifkan auto response untuk laporan WBS',
                'is_public' => false,
                'updated_by' => 1,
            ],
            [
                'key' => 'wbs_response_template',
                'value' => 'Terima kasih atas laporan yang Anda sampaikan. Laporan Anda akan ditindaklanjuti sesuai prosedur yang berlaku.',
                'type' => 'text',
                'group' => 'wbs',
                'description' => 'Template auto response WBS',
                'is_public' => false,
                'updated_by' => 1,
            ],
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'system',
                'description' => 'Mode maintenance website',
                'is_public' => false,
                'updated_by' => 1,
            ],
        ];

        foreach ($configurations as $config) {
            $config['created_at'] = now();
            $config['updated_at'] = now();
            DB::table('system_configurations')->insert($config);
        }
    }
}
