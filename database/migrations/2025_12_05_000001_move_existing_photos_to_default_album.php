<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Album;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create default album for existing photos
        $defaultAlbum = Album::create([
            'nama_album' => 'Galeri Umum',
            'slug' => 'galeri-umum',
            'deskripsi' => 'Album untuk foto-foto galeri yang sudah ada sebelumnya',
            'status' => true,
            'urutan' => 0,
            'tanggal_kegiatan' => now(),
        ]);

        // Move all existing photos without album to default album
        DB::table('galeris')
            ->whereNull('album_id')
            ->update(['album_id' => $defaultAlbum->id]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove album_id from photos that were in default album
        $defaultAlbum = Album::where('slug', 'galeri-umum')->first();
        
        if ($defaultAlbum) {
            DB::table('galeris')
                ->where('album_id', $defaultAlbum->id)
                ->update(['album_id' => null]);
            
            $defaultAlbum->delete();
        }
    }
};
