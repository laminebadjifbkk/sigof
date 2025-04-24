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
        Schema::create('notifications_region', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('region')->unique();    // une seule notification par région
            $table->unsignedInteger('dernier_palier_notifie'); // ex : 20, 40, 60...
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications_region');
    }
};
