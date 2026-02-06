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
        Schema::table('produits', function (Blueprint $table) {
            // Vérifier si les colonnes n'existent pas déjà
            if (!Schema::hasColumn('produits', 'image_path')) {
                $table->string('image_path')->nullable()->after('quantite');
            }
            if (!Schema::hasColumn('produits', 'image_name')) {
                $table->string('image_name')->nullable()->after('image_path');
            }
            if (!Schema::hasColumn('produits', 'image_mime_type')) {
                $table->string('image_mime_type')->nullable()->after('image_name');
            }
            if (!Schema::hasColumn('produits', 'image_size')) {
                $table->unsignedBigInteger('image_size')->nullable()->after('image_mime_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'image_name', 'image_mime_type', 'image_size']);
        });
    }
};