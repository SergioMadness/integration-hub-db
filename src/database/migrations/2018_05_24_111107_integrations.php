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
        Schema::create('requests', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('application_id')->nullable();
            $table->jsonb('body');
            $table->enum('status', ['new', 'queue', 'need_another_attempt', 'success', 'failed'])->default('new');
            $table->jsonb('processing_info')->nullable();
            $table->string('request_type')->index();
            $table->text('response')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
            $table->foreign('application_id')
                ->on('api_clients')
                ->references('id')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('requests');
    }
};
