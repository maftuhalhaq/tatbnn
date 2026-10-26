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
            // Menambahkan kolom usia dengan tipe integer, dan boleh kosong (nullable)
            $table->integer('usia')->nullable()->after('tgl_lahir');
        });
    }

    public function down()
    {
        Schema::table('asesmens', function (Blueprint $table) {
            $table->dropColumn('usia');
        });
    }
};
