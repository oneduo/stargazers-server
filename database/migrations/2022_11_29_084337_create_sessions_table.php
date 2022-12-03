<?php

declare(strict_types=1);

use App\Enums\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('stargazer_id')->nullable();
            $table->timestamps();
        });

        Schema::create('package_session', function (Blueprint $table) {
            $table->unsignedBigInteger('package_id');
            $table->unsignedBigInteger('session_id');
            $table->dateTime('starred_at')->nullable();
            $table->string('status')->default(Status::PENDING->value);

            $table->primary(['package_id', 'session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_session');
        Schema::dropIfExists('sessions');
    }
};
