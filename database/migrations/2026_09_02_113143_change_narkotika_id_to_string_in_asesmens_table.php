<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('asesmens', function (Blueprint $table) {
            // Hapus foreign key jika ada (sesuaikan nama constraint-nya jika error)
            // $table->dropForeign(['narkotika_id']);

            $table->string('narkotika_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('asesmens', function (Blueprint $table) {
            // Mengembalikan ke integer (pastikan data kosong atau bisa di-cast ke INT saat rollback)
            // $table->foreignId('narkotika_id')->nullable()->constrained('narkotikas');
        });
    }
};
