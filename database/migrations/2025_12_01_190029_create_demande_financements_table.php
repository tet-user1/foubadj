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
        Schema::create('demande_financements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Informations personnelles
            $table->string('nom');
            $table->string('email');
            $table->string('telephone');
            $table->enum('type_utilisateur', ['producteur', 'acheteur', 'autre']);
            
            // Informations projet
            $table->string('titre_projet');
            $table->decimal('montant_demande', 12, 2);
            $table->integer('duree_remboursement');
            $table->text('description_projet');
            $table->text('utilisation_fonds');
            
            // Documents
            $table->string('piece_identite')->nullable();
            $table->string('justificatif_domicile')->nullable();
            $table->string('plan_affaires')->nullable();
            $table->json('autres_documents')->nullable();
            
            // Suivi
            $table->string('numero_dossier')->unique();
            $table->enum('statut', [
                'en_attente',
                'en_etude', 
                'en_traitement',
                'approuve',
                'refuse',
                'annule'
            ])->default('en_attente');
            
            $table->text('notes_admin')->nullable();
            $table->boolean('newsletter_optin')->default(false);
            
            // Dates importantes
            $table->timestamp('date_approbation')->nullable();
            $table->timestamp('date_refus')->nullable();
            $table->timestamp('date_annulation')->nullable();
            
            $table->timestamps();
            
            // Index
            $table->index('numero_dossier');
            $table->index('statut');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_financements');
    }
};