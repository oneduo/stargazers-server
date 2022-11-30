<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('url', 512);
            $table->timestamps();

            $table->unique(['name', 'url']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
