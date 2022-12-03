<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stargazers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('github_id');
            $table->string('username');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stargazers');
    }
};
