<?php

namespace App\Http\Controllers;

use App\Models\AplikasiDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Collection;

class ApplicationController extends Controller
{
    /**
     * Halaman daftar aplikasi
     */
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => 'nullable|string|max:150',
            'status' => 'nullable|in:Aktif,Down,Maintenance',
        ]);
        $search = trim($filters['search'] ?? '');
        $status = $filters['status'] ?? '';

        $applicationStats = AplikasiDetail::query()
            ->selectRaw('COUNT(DISTINCT id_aplikasi) as total')
            ->selectRaw(
                'COUNT(DISTINCT CASE WHEN status_aplikasi = ? THEN id_aplikasi END) as active',
                ['Aktif']
            )
            ->selectRaw(
                'COUNT(DISTINCT CASE WHEN status_aplikasi = ? THEN id_aplikasi END) as down',
                ['Down']
            )
            ->first();

        $applications = AplikasiDetail::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('nama_aplikasi', 'like', '%' . $search . '%');
            })
            ->when($status !== '', function ($query) use ($status) {
                $query->where('status_aplikasi', $status);
            })
            ->orderBy('id_aplikasi')
            ->get()
            ->map(function ($a) {
                return [
                    'id' => $a->id_aplikasi,
                    'name' => $a->nama_aplikasi,
                    'server_name' => $a->nama_server ?? '-',
                    'status' => $a->status_aplikasi,
                    'domain' => $a->nama_domain ?? '-',
                    'licenses' => $a->license_name ?: '-',
                    'next_maintenance' => $a->next_maintenance ?? '-',
                ];
            })
            ->toArray();

        return view('pages.application.application', compact(
            'applications',
            'applicationStats',
            'search',
            'status'
        ));
    }

    
    /** Halaman detail aplikasi **/
    public function show(int $id)
    {
        $app = AplikasiDetail::where('id_aplikasi', $id)->first();

        abort_unless($app, 404);

        $applicationDomainIds = DB::table('domain')
            ->where('id_aplikasi', $id)
            ->whereNull('deleted_at')
            ->select('id_domain');
        $domainActivities = DB::table('vw_domain_activity')
            ->whereIn('id_domain', $applicationDomainIds)
            ->orderByDesc('tanggal')
            ->get()
            ->groupBy('id_domain');
        $domainDetails = DB::table('vw_domain_detail')
            ->whereIn('id_domain', $applicationDomainIds)
            ->orderBy('id_domain')
            ->get()
            ->map(function ($domain) use ($domainActivities) {
                return $this->formatApplicationDomainDetails(
                    $domain,
                    $domainActivities->get($domain->id_domain, collect())
                );
            });
        $applicationTechnologies = DB::table('teknologi_aplikasi')
            ->where('id_aplikasi', $id)
            ->orderBy('id_teknologi_aplikasi')
            ->get([
                'id_teknologi_aplikasi',
                'bahasa_pemrograman',
                'kerangka_pengembangan',
                'metode_pengembangan',
                'model_pengembangan',
                'basis_data',
                'sistem_operasi_server',
                'layanan_infrastruktur',
                'mekanisme_pembangunan',
                'keterangan',
            ]);
        if ($applicationTechnologies->isEmpty()) {
            $applicationTechnologies = collect([
                (object) array_merge(
                    ['id_teknologi_aplikasi' => null],
                    array_fill_keys([
                        'bahasa_pemrograman',
                        'kerangka_pengembangan',
                        'metode_pengembangan',
                        'model_pengembangan',
                        'basis_data',
                        'sistem_operasi_server',
                        'layanan_infrastruktur',
                        'mekanisme_pembangunan',
                        'keterangan',
                    ], null)
                ),
            ]);
        }

        /* Ambil maintenance terakhir & berikutnya */
        $lastMaintenance = DB::table('vw_maintenance')
            ->where('target_type', 'Application')
            ->where('nama_target', $app->nama_aplikasi)
            ->where('status', 'Completed')
            ->orderBy('jadwal_tanggal', 'desc')
            ->first();

        $nextMaintenance = DB::table('vw_maintenance')
            ->where('target_type', 'Application')
            ->where('nama_target', $app->nama_aplikasi)
            ->whereIn('status', ['Scheduled', 'In Progress'])
            ->orderBy('jadwal_tanggal', 'asc')
            ->first();

        $maintenanceHistory = DB::table('vw_maintenance')
            ->where('target_type', 'Application')
            ->where('nama_target', $app->nama_aplikasi)
            ->orderBy('jadwal_tanggal', 'desc')
            ->limit(10)
            ->get();

        $application = [
            'id' => $app->id_aplikasi,
            'name' => $app->nama_aplikasi,
            'status' => $app->status_aplikasi,
            'description' => $app->deskripsi ?: '-',
            'code' => $app->kode_aplikasi,
            'category' => $app->kategori ?? '-',
            'sla' => $app->sla_percent ? $app->sla_percent . '%' : '-',
            'version' => $app->versi ?? '-',
            'uptime' => $app->uptime_percent ? $app->uptime_percent . '%' : '0%',
            'uptime_period' => '30 Hari Terakhir',
            'since' => $app->tanggal_mulai_operasi 
                ? \Carbon\Carbon::parse($app->tanggal_mulai_operasi)->format('d F Y')
                : '-',
            'since_duration' => $app->tanggal_mulai_operasi 
                ? '(' . \Carbon\Carbon::parse($app->tanggal_mulai_operasi)->diffForHumans(null, true) . ')'
                : '',

            /* Server & Infrastruktur */
            'server' => $app->nama_server ?? '-',
            'ip_address' => $app->alamat_ip_produksi ?? '-',
            'os' => $app->sistem_operasi ?? '-',
            'database' => $app->database_digunakan ?? '-',

            /* Domain */
            'domain' => $app->nama_domain ?? '-',
            'ssl_certificate' => $app->status_ssl ?? 'Tidak Ada',
            'ssl_expired' => $app->ssl_kadaluarsa 
                ? \Carbon\Carbon::parse($app->ssl_kadaluarsa)->format('d F Y')
                : '-',
            'domain_status' => $app->status_domain ?? 'Tidak Aktif',

            /* Licenses (LENGKAP) */
            'license_name' => $app->license_name ?? '-',
            'license_provider' => $app->license_provider ?? '-',
            'license_count' => $app->license_count ? $app->license_count . ' License' : '-',
            'license_expired' => $app->license_expired 
                ? \Carbon\Carbon::parse($app->license_expired)->format('d M Y')
                : '-',
            'license_expired_note' => $app->license_expired 
                ? '(' . \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($app->license_expired), false) . ' Hari Lagi)'
                : '',
            'license_status' => $app->license_status ?? '-',

            /* Pengurus / PIC */
            'pic_name' => $app->nama_pic ?? '-',
            'pic_jabatan' => $app->pic_jabatan ?? '-',
            'pic_email' => $app->pic_email ?? '-',
            'pic_telepon' => $app->pic_telepon ?? '-',
            'pic_divisi' => $app->pic_divisi ?? '-', 

            /* Maintenance Terakhir */
            'last_maintenance' => $lastMaintenance ? [
                'id' => $lastMaintenance->id_maintenance,
                'tanggal' => \Carbon\Carbon::parse($lastMaintenance->jadwal_tanggal)->format('d F Y'),
                'jenis' => $lastMaintenance->jenis_maintenance,
                'pic' => $lastMaintenance->nama_pic ?? '-',
                'id_pic' => $lastMaintenance->id_pic ?? null,
                'status' => $lastMaintenance->status,
            ] : null,

            /* Maintenance Berikutnya */
            'next_maintenance' => $nextMaintenance ? [
                'id' => $nextMaintenance->id_maintenance,
                'tanggal' => \Carbon\Carbon::parse($nextMaintenance->jadwal_tanggal)->format('d F Y'),
                'tanggal_input' => \Carbon\Carbon::parse($nextMaintenance->jadwal_tanggal)->format('Y-m-d'),
                'jenis' => $nextMaintenance->jenis_maintenance,
                'pic' => $nextMaintenance->nama_pic ?? '-',
                'id_pic' => $nextMaintenance->id_pic ?? null,
                'status' => $nextMaintenance->status,
            ] : null,

            'pics' => DB::table('pic')->orderBy('nama_pic')->pluck('nama_pic', 'id_pic'),

            /* Riwayat Maintenance */
            'maintenance_history' => $maintenanceHistory->map(function ($m) {
                return [
                    'id' => $m->id_maintenance,
                    'tanggal' => \Carbon\Carbon::parse($m->jadwal_tanggal)->format('d F Y'),
                    'jenis' => $m->jenis_maintenance,
                    'status' => $m->status,
                ];
            })->toArray(),
        ];

        return view('pages.application.detail', compact(
            'application',
            'domainDetails',
            'applicationTechnologies'
        ));
    }

    /**
     * Halaman form tambah aplikasi
     */
    public function create()
    {
        $servers = DB::table('server')
            ->whereNull('deleted_at')
            ->orderBy('nama_server')
            ->pluck('nama_server', 'id_server');

        $licenses = DB::table('lisensi')
            ->whereNull('deleted_at')
            ->orderBy('nama_lisensi')
            ->get(['id_lisensi', 'nama_lisensi', 'provider']);

        $domains = DB::table('domain')
            ->whereNull('domain.id_aplikasi')
            ->orderBy('domain.nama_domain')
            ->get(['domain.id_domain', 'domain.nama_domain']);

        return view('pages.application.create', compact('servers', 'licenses', 'domains'));
    }

    /**
     * Proses simpan aplikasi baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_aplikasi' => 'required|string|max:20|unique:aplikasi,kode_aplikasi',
            'nama_aplikasi' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'kategori' => 'nullable|string|max:50',
            'versi' => 'nullable|string|max:20',
            'status' => 'required|in:Aktif,Down,Maintenance',
            'sla_percent' => 'nullable|numeric|min:0|max:100',
            'uptime_percent' => 'nullable|numeric|min:0|max:100',
            'database_digunakan' => 'nullable|string|max:50',
            'tanggal_mulai_operasi' => 'nullable|date',
            'id_server' => [
                'nullable',
                'integer',
                Rule::exists('server', 'id_server')->whereNull('deleted_at'),
            ],
            'id_domain' => [
                'nullable',
                'integer',
                Rule::exists('domain', 'id_domain')->whereNull('id_aplikasi'),
            ],
            'id_lisensi' => [
                'nullable',
                'integer',
                Rule::exists('lisensi', 'id_lisensi')->whereNull('deleted_at'),
            ],
        ]);

        $licenseId = $request->filled('id_lisensi') ? (int) $request->input('id_lisensi') : null;
        if ($licenseId !== null && ! DB::table('server')->where('id_server', $request->id_server)->whereNotNull('id_aset')->exists()) {
            return back()->withErrors([
                'id_lisensi' => 'Pilih server yang memiliki aset untuk menghubungkan lisensi.',
            ])->withInput();
        }

        try {
            DB::transaction(function () use ($request, $licenseId) {
                $applicationId = DB::table('aplikasi')->insertGetId([
                    'kode_aplikasi' => $request->kode_aplikasi,
                    'nama_aplikasi' => $request->nama_aplikasi,
                    'id_server' => $request->id_server,
                    'deskripsi' => $request->deskripsi,
                    'kategori' => $request->kategori,
                    'versi' => $request->versi,
                    'status' => $request->status,
                    'sla_percent' => $request->sla_percent,
                    'uptime_percent' => $request->uptime_percent,
                    'database_digunakan' => $request->database_digunakan,
                    'tanggal_mulai_operasi' => $request->tanggal_mulai_operasi,
                    'dibuat_pada' => now(),
                ], 'id_aplikasi');

                $this->saveApplicationDomain(
                    $applicationId,
                    null,
                    $request->filled('id_domain') ? (int) $request->input('id_domain') : null
                );
                $this->assignServerLicense($request->id_server, $licenseId);
            });

            return redirect()->route('application.index')
                ->with('success', 'Aplikasi berhasil ditambahkan!');

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan aplikasi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Halaman form edit aplikasi
     */
    public function edit(int $id)
    {
        $app = DB::table('aplikasi')
            ->where('id_aplikasi', $id)
            ->whereNull('deleted_at')
            ->first();

        abort_unless($app, 404);

        $servers = DB::table('server')
            ->whereNull('deleted_at')
            ->orderBy('nama_server')
            ->pluck('nama_server', 'id_server');

        $licenses = DB::table('lisensi')
            ->whereNull('deleted_at')
            ->orderBy('nama_lisensi')
            ->get(['id_lisensi', 'nama_lisensi', 'provider']);

        $currentDomainId = DB::table('domain')
            ->where('id_aplikasi', $id)
            ->value('id_domain');
        $domains = DB::table('domain')
            ->where(function ($query) use ($id) {
                $query->whereNull('id_aplikasi')
                    ->orWhere('id_aplikasi', $id);
            })
            ->orderBy('nama_domain')
            ->get(['id_domain', 'nama_domain']);
        $currentAssetId = $app->id_server
            ? DB::table('server')->where('id_server', $app->id_server)->value('id_aset')
            : null;
        $selectedLicenseId = $currentAssetId
            ? DB::table('lisensi_aset')
                ->where('id_aset', $currentAssetId)
                ->orderByDesc('id_lisensi_aset')
                ->value('id_lisensi')
            : null;

        return view('pages.application.edit', compact(
            'app',
            'servers',
            'licenses',
            'domains',
            'currentDomainId',
            'selectedLicenseId'
        ));
    }

    /**
     * Proses update aplikasi
     */
    public function update(Request $request, int $id)
    {
        $currentDomainId = DB::table('domain')
            ->where('id_aplikasi', $id)
            ->value('id_domain');

        $request->validate([
            'kode_aplikasi' => 'required|string|max:20|unique:aplikasi,kode_aplikasi,' . $id . ',id_aplikasi',
            'nama_aplikasi' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'kategori' => 'nullable|string|max:50',
            'versi' => 'nullable|string|max:20',
            'status' => 'required|in:Aktif,Down,Maintenance',
            'sla_percent' => 'nullable|numeric|min:0|max:100',
            'uptime_percent' => 'nullable|numeric|min:0|max:100',
            'database_digunakan' => 'nullable|string|max:50',
            'tanggal_mulai_operasi' => 'nullable|date',
            'id_server' => [
                'nullable',
                'integer',
                Rule::exists('server', 'id_server')->whereNull('deleted_at'),
            ],
            'id_domain' => [
                'nullable',
                'integer',
                Rule::exists('domain', 'id_domain')->where(function ($query) use ($id) {
                    $query->whereNull('id_aplikasi')
                        ->orWhere('id_aplikasi', $id);
                }),
            ],
            'id_lisensi' => [
                'nullable',
                'integer',
                Rule::exists('lisensi', 'id_lisensi')->whereNull('deleted_at'),
            ],
        ]);

        $licenseId = $request->filled('id_lisensi') ? (int) $request->input('id_lisensi') : null;
        if ($licenseId !== null && ! DB::table('server')->where('id_server', $request->id_server)->whereNotNull('id_aset')->exists()) {
            return back()->withErrors([
                'id_lisensi' => 'Pilih server yang memiliki aset untuk menghubungkan lisensi.',
            ])->withInput();
        }
        try {
            DB::transaction(function () use ($request, $id, $licenseId, $currentDomainId) {
                DB::table('aplikasi')
                    ->where('id_aplikasi', $id)
                    ->update([
                        'kode_aplikasi' => $request->kode_aplikasi,
                        'nama_aplikasi' => $request->nama_aplikasi,
                        'id_server' => $request->id_server,
                        'deskripsi' => $request->deskripsi,
                        'kategori' => $request->kategori,
                        'versi' => $request->versi,
                        'status' => $request->status,
                        'sla_percent' => $request->sla_percent,
                        'uptime_percent' => $request->uptime_percent,
                        'database_digunakan' => $request->database_digunakan,
                        'tanggal_mulai_operasi' => $request->tanggal_mulai_operasi,
                        'diperbarui_pada' => now(),
                    ]);

                $this->saveApplicationDomain(
                    $id,
                    $currentDomainId ? (int) $currentDomainId : null,
                    $request->filled('id_domain') ? (int) $request->input('id_domain') : null
                );
                $this->assignServerLicense($request->id_server, $licenseId);
            });

            return redirect()->route('application.index')
                ->with('success', 'Aplikasi berhasil diupdate!');

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupdate aplikasi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Soft delete aplikasi
     */
    public function destroy(int $id)
    {
        try {
            DB::table('aplikasi')
                ->where('id_aplikasi', $id)
                ->update(['deleted_at' => now()]);

            return redirect()->route('application.index')
                ->with('success', 'Aplikasi berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus aplikasi: ' . $e->getMessage());
        }
    }

    public function storeTechnology(Request $request, int $id)
    {
        abort_unless(DB::table('aplikasi')->where('id_aplikasi', $id)->whereNull('deleted_at')->exists(), 404);

        $validated = $request->validate($this->technologyRules());

        DB::table('teknologi_aplikasi')->insert(
            ['id_aplikasi' => $id] + $validated
        );

        return redirect()
            ->route('application.detail', ['id' => $id, 'tab' => 'technology'])
            ->with('success', 'Teknologi aplikasi berhasil ditambahkan.');
    }

    public function updateTechnology(Request $request, int $id, int $technologyId)
    {
        abort_unless(DB::table('aplikasi')->where('id_aplikasi', $id)->whereNull('deleted_at')->exists(), 404);

        $technology = DB::table('teknologi_aplikasi')
            ->where('id_teknologi_aplikasi', $technologyId)
            ->where('id_aplikasi', $id)
            ->exists();
        abort_unless($technology, 404);

        $validated = $request->validate($this->technologyRules());

        DB::table('teknologi_aplikasi')
            ->where('id_teknologi_aplikasi', $technologyId)
            ->where('id_aplikasi', $id)
            ->update($validated);

        return redirect()
            ->route('application.detail', ['id' => $id, 'tab' => 'technology'])
            ->with('success', 'Teknologi aplikasi berhasil diperbarui.');
    }

    private function technologyRules(): array
    {
        return [
            'bahasa_pemrograman' => 'nullable|string|max:200',
            'kerangka_pengembangan' => 'nullable|string|max:200',
            'metode_pengembangan' => 'nullable|string|max:200',
            'model_pengembangan' => 'nullable|string|max:200',
            'basis_data' => 'nullable|string|max:200',
            'sistem_operasi_server' => 'nullable|string|max:200',
            'layanan_infrastruktur' => 'nullable|string|max:200',
            'mekanisme_pembangunan' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
        ];
    }

    private function formatApplicationDomainDetails(object $domain, Collection $activities): array
    {
        return [
            'id' => $domain->id_domain,
            'domain' => $domain->nama_domain,
            'status' => $domain->status_domain ?? '-',
            'purchase_date' => $domain->tanggal_pembelian
                ? Carbon::parse($domain->tanggal_pembelian)->format('d F Y')
                : '-',
            'expiration_date' => $domain->tanggal_kadaluarsa
                ? Carbon::parse($domain->tanggal_kadaluarsa)->format('d F Y')
                : '-',
            'auto_renewal' => $domain->perpanjangan_otomatis ? 'Active' : 'inactive',
            'domain_status' => $domain->status_domain === 'Aktif' ? 'Normal' : 'Inactive',
            'registrar' => $domain->registrar ?? '-',
            'pic_name' => $domain->nama_pic ?? '-',
            'pic_jabatan' => $domain->pic_jabatan ?? '-',
            'pic_email' => $domain->pic_email ?? '-',
            'pic_telepon' => $domain->pic_telepon ?? '-',
            'ssl_provider' => $domain->ssl_provider ?? '-',
            'ssl_status' => $domain->ssl_status ?? '-',
            'ssl_issued_date' => $domain->ssl_tanggal_terbit
                ? Carbon::parse($domain->ssl_tanggal_terbit)->format('d F Y')
                : '-',
            'ssl_expiration_date' => $domain->ssl_tanggal_kadaluarsa
                ? Carbon::parse($domain->ssl_tanggal_kadaluarsa)->format('d F Y')
                : '-',
            'ssl_certificate_type' => $domain->ssl_tipe ?? '-',
            'billing_status' => $domain->billing_status ?? 'Unpaid',
            'billing_cycle' => $domain->billing_cycle ?? '1 tahun',
            'last_payment' => $domain->last_payment
                ? Carbon::parse($domain->last_payment)->format('d F Y')
                : '-',
            'next_due_date' => $domain->next_due_date
                ? Carbon::parse($domain->next_due_date)->format('d F Y')
                : '-',
            'amount' => $domain->amount ? number_format($domain->amount, 0, ',', ',') : '-',
            'payment_method' => $domain->payment_method ?? '-',
            'reminders' => $this->buildApplicationDomainReminders($domain),
            'activity_history' => $activities->map(function ($activity) {
                return [
                    'tanggal' => $activity->tanggal
                        ? Carbon::parse($activity->tanggal)->format('d F Y')
                        : '-',
                    'aktifitas' => $activity->aktivitas ?? '-',
                    'pic' => $activity->nama_pic ?? '-',
                    'status' => $activity->status ?? '-',
                    'catatan' => $activity->catatan ?? '-',
                ];
            })->toArray(),
        ];
    }

    private function buildApplicationDomainReminders(object $domain): array
    {
        $reminders = [];

        if ($domain->sisa_hari_domain !== null && $domain->sisa_hari_domain <= 60) {
            $reminders[] = [
                'type' => 'warning',
                'title' => 'Domain akan expired',
                'description' => $domain->nama_domain.' akan expired dalam '.$domain->sisa_hari_domain.' hari',
                'date' => $domain->tanggal_kadaluarsa
                    ? Carbon::parse($domain->tanggal_kadaluarsa)->format('d M Y')
                    : '-',
            ];
        }

        if ($domain->ssl_tanggal_kadaluarsa) {
            $daysUntilSslExpiration = Carbon::now()->diffInDays(
                Carbon::parse($domain->ssl_tanggal_kadaluarsa),
                false
            );

            if ($daysUntilSslExpiration <= 60) {
                $reminders[] = [
                    'type' => 'info',
                    'title' => 'SSL Certificate akan expired',
                    'description' => 'Sertifikat SSL akan expired dalam '.round($daysUntilSslExpiration).' hari',
                    'date' => Carbon::parse($domain->ssl_tanggal_kadaluarsa)->format('d M Y'),
                ];
            }
        }

        $reminders[] = [
            'type' => 'info',
            'title' => 'Perpanjang Domain',
            'description' => 'Jadwalkan perpanjangan domain berikutnya',
            'date' => $domain->tanggal_kadaluarsa
                ? Carbon::parse($domain->tanggal_kadaluarsa)->format('d M Y')
                : '-',
        ];

        return $reminders;
    }

    private function saveApplicationDomain(int $applicationId, ?int $currentDomainId, ?int $domainId): void
    {
        if ($domainId !== null) {
            $domain = DB::table('domain')
                ->where('id_domain', $domainId)
                ->lockForUpdate()
                ->first();

            if (! $domain || ($domain->id_aplikasi !== null && (int) $domain->id_aplikasi !== $applicationId)) {
                throw ValidationException::withMessages([
                    'id_domain' => 'Domain tersebut sudah digunakan oleh aplikasi lain.',
                ]);
            }

            if ((int) ($domain->id_aplikasi ?? 0) !== $applicationId) {
                DB::table('domain')
                    ->where('id_domain', $domainId)
                    ->update(['id_aplikasi' => $applicationId]);
            }
        }

        if ($currentDomainId !== null && $currentDomainId !== $domainId) {
            DB::table('domain')
                ->where('id_domain', $currentDomainId)
                ->where('id_aplikasi', $applicationId)
                ->update(['id_aplikasi' => null]);
        }
    }

    private function assignServerLicense(?int $serverId, ?int $licenseId): void
    {
        if ($serverId === null || $licenseId === null) {
            return;
        }

        $assetId = DB::table('server')->where('id_server', $serverId)->value('id_aset');
        if ($assetId === null) {
            return;
        }

        $alreadyAssigned = DB::table('lisensi_aset')
            ->where('id_aset', $assetId)
            ->where('id_lisensi', $licenseId)
            ->exists();

        if (! $alreadyAssigned) {
            DB::table('lisensi_aset')->insert([
                'id_lisensi' => $licenseId,
                'id_aset' => $assetId,
                'tanggal_pemasangan' => now()->toDateString(),
            ]);
        }
    }

    /**
 * Update PIC aplikasi (dari modal di card Pengurus/PIC)
 */
public function updatePic(Request $request, int $id)
{
    $request->validate([
        'id_pic' => 'nullable|integer|exists:pic,id_pic',
    ]);

    try {
        DB::table('aplikasi')
            ->where('id_aplikasi', $id)
            ->update([
                'id_pic' => $request->id_pic,
                'diperbarui_pada' => now(),
            ]);

        return redirect()->back()->with('success', 'PIC aplikasi berhasil diupdate!');

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Gagal mengupdate PIC: ' . $e->getMessage())
            ->withInput();
    }
}

/**
 * Tambah PIC baru ke master PIC
 */
public function storePic(Request $request)
{
    $request->validate([
        'nama_pic' => 'required|string|max:100',
        'jabatan' => 'nullable|string|max:100',
        'divisi' => 'nullable|string|max:100',
        'email' => 'nullable|email|max:100',
        'telepon' => 'nullable|string|max:30',
        'id_aplikasi' => 'required|integer|exists:aplikasi,id_aplikasi',
    ]);

    try {
        DB::transaction(function () use ($request) {
            /* Insert PIC baru ke tabel pic */
            $idPic = DB::table('pic')->insertGetId([
                'nama_pic' => $request->nama_pic,
                'jabatan' => $request->jabatan,
                'divisi' => $request->divisi,
                'email' => $request->email,
                'telepon' => $request->telepon,
            ]);

            /* Update aplikasi pake PIC baru */
            DB::table('aplikasi')
                ->where('id_aplikasi', $request->id_aplikasi)
                ->update([
                    'id_pic' => $idPic,
                    'diperbarui_pada' => now(),
                ]);
        });

        return redirect()->back()->with('success', 'PIC baru berhasil ditambahkan!');

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Gagal menambahkan PIC: ' . $e->getMessage())
            ->withInput();
    }
}
}