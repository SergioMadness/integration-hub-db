<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProcessFlow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('flow', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('company_id');
            $table->string('name');
            $table->jsonb('data');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'is_default']);
            $table->foreign('company_id')
                ->on('company')
                ->references('id')
                ->onDelete('cascade');
        });

        Schema::create('process_options', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('company_id');
            $table->string('subsystem_id');
            $table->string('name');
            $table->jsonb('mapping');
            $table->jsonb('options');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')
                ->on('company')
                ->references('id')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('flow');
        Schema::drop('process_options');
    }
}
