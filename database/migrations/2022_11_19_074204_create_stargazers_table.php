<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stargazers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('github_id')->unique();
            $table->string('username')->unique();
            $table->timestamps();

            $table->unique(['github_id', 'username']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stargazers');
    }
};
