<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('server') && ! Schema::hasColumn('server', 'server_role')) {
            Schema::table('server', function (Blueprint $table) {
                $table->string('server_role', 255)->nullable();
            });
        }

        if (Schema::hasTable('aset') && ! Schema::hasColumn('aset', 'tanggal_garansi')) {
            Schema::table('aset', function (Blueprint $table) {
                $table->date('tanggal_garansi')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('server') && Schema::hasColumn('server', 'server_role')) {
            Schema::table('server', function (Blueprint $table) {
                $table->dropColumn('server_role');
            });
        }

        if (Schema::hasTable('aset') && Schema::hasColumn('aset', 'tanggal_garansi')) {
            Schema::table('aset', function (Blueprint $table) {
                $table->dropColumn('tanggal_garansi');
            });
        }
    }
};
