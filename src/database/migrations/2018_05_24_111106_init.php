<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('api_clients', static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('key');
            $table->boolean('is_system')->default(false);
            $table->uuid('owner_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('api_clients');
    }
};
