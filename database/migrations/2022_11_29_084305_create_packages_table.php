<?php

declare(strict_types=1);

use App\Enums\PackageType;
use App\Models\Package;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('url', 512);
            $table->string('type')->index()->nullable();
            $table->timestamps();

            $table->unique(['name', 'type']);
        });

        Package::query()->create([
            'name' => 'oneduo/stargazers',
            'url' => 'https://github.com/oneduo/stargazers',
            'type' => PackageType::OTHER,
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
