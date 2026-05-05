<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('portal_papua_tengahs', function (Blueprint $table) {
            // Add thumbnail column after gambar
            $table->string('thumbnail')->nullable()->after('gambar');

            // Add slug column (for SEO-friendly URLs)
            $table->string('slug')->nullable()->unique()->after('judul');

            // Add penulis column (author name)
            $table->string('penulis')->nullable()->after('author');

            // Add is_published column for draft/published status
            $table->boolean('is_published')->default(false)->after('status');

            // Add is_featured column for featured articles
            $table->boolean('is_featured')->default(false)->after('is_published');

            // Add published_at column for publication timestamp
            $table->timestamp('published_at')->nullable()->after('is_featured');

            // Add tags column (comma-separated tags)
            $table->text('tags')->nullable()->after('published_at');

            // Add meta_description for SEO
            $table->string('meta_description', 160)->nullable()->after('tags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portal_papua_tengahs', function (Blueprint $table) {
            $table->dropColumn([
                'thumbnail',
                'slug',
                'penulis',
                'is_published',
                'is_featured',
                'published_at',
                'tags',
                'meta_description',
            ]);
        });
    }
};

