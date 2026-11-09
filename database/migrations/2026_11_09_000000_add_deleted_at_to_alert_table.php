<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('alert', 'deleted_at')) {
            Schema::table('alert', function (Blueprint $table) {
                $table->timestamp('deleted_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('alert', 'deleted_at')) {
            Schema::table('alert', function (Blueprint $table) {
                $table->dropColumn('deleted_at');
            });
        }
    }
};