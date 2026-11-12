<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('aset') && ! Schema::hasColumn('aset', 'id_lokasi_aset')) {
            Schema::table('aset', function (Blueprint $table) {
                $table->unsignedInteger('id_lokasi_aset')->nullable()->index();
            });
        }

        if (! Schema::hasTable('aset') || ! Schema::hasTable('lokasi_aset')) {
            return;
        }

        Schema::table('lokasi_aset', function (Blueprint $table) {
            $table->unsignedInteger('id_aset')->nullable()->change();
        });

        $locations = DB::table('lokasi_aset')
            ->whereNull('deleted_at')
            ->orderBy('id_lokasi_aset')
            ->get([
                'id_aset',
                'id_lokasi_aset',
                'id_data_center',
                'nama_gedung',
                'lantai',
                'nama_ruangan',
                'zona',
                'kode_rak',
                'nama_rak',
            ]);
        $canonicalLocations = [];
        $identityFields = [
            'id_data_center',
            'nama_gedung',
            'lantai',
            'nama_ruangan',
            'zona',
            'kode_rak',
            'nama_rak',
        ];
        $hasLocationDetails = ['nama_gedung', 'nama_ruangan', 'kode_rak', 'nama_rak'];

        foreach ($locations as $location) {
            if ($location->id_aset !== null) {
                DB::table('aset')
                    ->where('id_aset', $location->id_aset)
                    ->update(['id_lokasi_aset' => $location->id_lokasi_aset]);
            }

            if (! collect($hasLocationDetails)->contains(fn ($field) => filled($location->$field))) {
                $canonicalLocations[] = $location->id_lokasi_aset;
                continue;
            }

            $identity = array_map(fn ($field) => $location->$field, $identityFields);
            $key = json_encode($identity, JSON_THROW_ON_ERROR);

            if (! array_key_exists($key, $canonicalLocations)) {
                $canonicalLocations[$key] = $location->id_lokasi_aset;
                continue;
            }

            $canonicalId = $canonicalLocations[$key];
            DB::table('aset')
                ->where('id_lokasi_aset', $location->id_lokasi_aset)
                ->update(['id_lokasi_aset' => $canonicalId]);
            DB::table('lokasi_aset')
                ->where('id_lokasi_aset', $location->id_lokasi_aset)
                ->update(['deleted_at' => now()]);
        }

        DB::table('lokasi_aset')->update(['id_aset' => null]);
    }

    public function down(): void
    {
        if (Schema::hasTable('aset')
            && Schema::hasTable('lokasi_aset')
            && Schema::hasColumn('aset', 'id_lokasi_aset')) {
            DB::table('aset')
                ->whereNotNull('id_lokasi_aset')
                ->orderBy('id_aset')
                ->get(['id_aset', 'id_lokasi_aset'])
                ->each(function ($asset) {
                    DB::table('lokasi_aset')
                        ->where('id_lokasi_aset', $asset->id_lokasi_aset)
                        ->update(['id_aset' => $asset->id_aset]);
                });
        }

        if (Schema::hasTable('aset') && Schema::hasColumn('aset', 'id_lokasi_aset')) {
            Schema::table('aset', function (Blueprint $table) {
                $table->dropColumn('id_lokasi_aset');
            });
        }
    }
};
