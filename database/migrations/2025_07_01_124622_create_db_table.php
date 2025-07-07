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
        Schema::create('surfaces', function(Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('fields', function(Blueprint $table) {
            $table->id();
            $table->string('nome_campo');
            $table->text('descrizione')->nullable();
            $table->unsignedBigInteger('id_superficie');
            $table->time('orario_apertura');
            $table->time('orario_chiusura');
            $table->timestamps();
        });

        Schema::create('reservations', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('id_campo');
            $table->date('data_prenotazione');
            $table->time('ora_inizio');
            $table->time('ora_fine');
            $table->enum('stato', ['attesa', 'accettata', 'rifiutata'])->default('attesa');
            $table->timestamps();
        });

        Schema::table('fields', function(Blueprint $table) {
            $table->foreign('id_superficie')
                    ->references('id')
                    ->on('surfaces');
        });

        Schema::table('reservations', function(Blueprint $table) {
            $table->foreign('id_campo')
                    ->references('id')
                    ->on('fields');
            
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('db_tables');
    }
};
