<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paniers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->integer('quantite');
            $table->timestamps();

            // Index + contrainte unique
            $table->index(['user_id', 'produit_id']);
            $table->unique(['user_id', 'produit_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('paniers');
    }
};
