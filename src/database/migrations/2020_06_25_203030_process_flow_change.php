<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProcessFlowChange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('process_options', function (Blueprint $table) {
            $table->dropColumn('subsystem_id');
            $table->string('subsystem_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('process_options', function (Blueprint $table) {
            $table->dropColumn('subsystem_id');
            $table->uuid('subsystem_id');
        });
    }
}
