<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stargazers', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('github_id')->nullable();
            $table->timestamps();
        });

        Schema::create('package_stargazer', function (Blueprint $table) {
            $table->unsignedBigInteger('package_id');
            $table->unsignedBigInteger('stargazer_id');
            $table->dateTime('starred_at')->nullable();

            $table->primary(['package_id', 'stargazer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_stargazer');
        Schema::dropIfExists('stargazers');
    }
};
