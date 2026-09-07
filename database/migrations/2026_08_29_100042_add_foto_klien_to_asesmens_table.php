<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('asesmens', function (Blueprint $table) {
        $table->string('foto_klien')->nullable()->after('nama_lengkap');
    });
}

public function down()
{
    Schema::table('asesmens', function (Blueprint $table) {
        $table->dropColumn('foto_klien');
    });
}
};
