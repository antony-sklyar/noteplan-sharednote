<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('published_notes', function (Blueprint $table) {
            $table->string('access_key')->nullable()->after('guid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('published_notes', function (Blueprint $table) {
            $table->dropColumn('access_key');
        });
    }
};
