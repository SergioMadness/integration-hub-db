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
        Schema::table('process_options', static function (Blueprint $table) {
            $table->dropColumn('subsystem_id');
        });
        Schema::table('process_options', static function (Blueprint $table) {
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
        Schema::table('process_options', static function (Blueprint $table) {
            $table->dropColumn('subsystem_id');
        });
        Schema::table('process_options', static function (Blueprint $table) {
            $table->uuid('subsystem_id');
        });
    }
}
