<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('penyedia') && ! Schema::hasColumn('penyedia', 'deleted_at')) {
            Schema::table('penyedia', function (Blueprint $table) {
                $table->timestamp('deleted_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('penyedia') && Schema::hasColumn('penyedia', 'deleted_at')) {
            Schema::table('penyedia', function (Blueprint $table) {
                $table->dropColumn('deleted_at');
            });
        }
    }
};
