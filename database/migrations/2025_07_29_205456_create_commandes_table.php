<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('numero_commande')->unique();
            $table->enum('statut', [
                'en_attente',
                'confirmee', 
                'en_preparation',
                'en_livraison',
                'livree',
                'annulee'
            ])->default('en_attente');
            $table->decimal('total', 10, 2);
            $table->integer('nombre_articles');
            $table->datetime('date_commande');
            $table->datetime('date_livraison')->nullable();

            // Livraison
            $table->string('adresse_livraison')->nullable();
            $table->string('ville_livraison')->nullable();
            $table->string('telephone_livraison')->nullable();

            // Paiement
            $table->enum('mode_paiement', [
                'especes',
                'mobile_money',
                'carte_bancaire',
                'virement'
            ])->nullable();
            $table->enum('statut_paiement', [
                'en_attente',
                'paye',
                'rembourse'
            ])->default('en_attente');

            // Notes
            $table->text('notes_client')->nullable();
            $table->text('notes_admin')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('commandes');
    }
};
