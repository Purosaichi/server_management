<?php


namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AlertController extends Controller
{
    public function index()
    {
        /* STATISTIK DARI VIEW */
        $statsRaw = DB::table('vw_dashboard_stats')->first();

        $stats = [
            'total'    => $statsRaw->alert_aktif ?? 0,
            'critical' => $statsRaw->alert_critical ?? 0,
            'warning'  => DB::table('alert')->where('level', 'Warning')->where('status', 'Aktif')->count(),
            'info'     => DB::table('alert')->where('level', 'Info')->where('status', 'Aktif')->count(),
        ];

        /* AMBIL DATA ALERT DARI VIEW */
        $allAlerts = DB::table('vw_alert')
            ->orderBy('waktu_mulai', 'desc')
            ->get()
            ->map(function ($row) {
                return [
                    'id'         => $row->id_alert ?? $row->id ?? null,
                    'target_id'  => $row->id_server ?? $row->id_aplikasi ?? $row->id_domain ?? $row->id_target ?? null,
                    'target_type'=> $row->target_type,
                    'pic_id'     => $row->id_pic ?? null,
                    'waktu'      => \Carbon\Carbon::parse($row->waktu_mulai)->format('d M Y'),
                    'jam'        => \Carbon\Carbon::parse($row->waktu_mulai)->format('H:i') . 
                                    ($row->waktu_selesai ? ' - ' . \Carbon\Carbon::parse($row->waktu_selesai)->format('H:i') : ''),
                    'waktu_mulai_raw' => Carbon::parse($row->waktu_mulai)->format('Y-m-d\TH:i'),
                    'waktu_selesai_raw' => $row->waktu_selesai ? Carbon::parse($row->waktu_selesai)->format('Y-m-d\TH:i') : '',
                    'level'      => $row->level,
                    'target'     => $row->nama_target ?? '-',
                    'target_sub' => $row->target_type,
                    'type'       => $row->target_type,
                    'deskripsi'  => $row->deskripsi,
                    'keterangan' => $row->keterangan ?? '',
                    'status'     => $row->status,
                    'durasi'     => $row->durasi_menit ? $row->durasi_menit . ' Menit' : '-',
                    'pic'        => $row->nama_pic ?? '-',
                ];
            });

        /* PISAHKAN PER KATEGORI */
        $serverAlerts      = $allAlerts->where('type', 'Server')->values()->toArray();
        $applicationAlerts = $allAlerts->where('type', 'Application')->values()->toArray();
        $domainAlerts      = $allAlerts->where('type', 'Domain')->values()->toArray();

        $servers = DB::table('server')->whereNull('deleted_at')->orderBy('nama_server')->get(['id_server', 'nama_server']);
        $applications = DB::table('aplikasi')->whereNull('deleted_at')->orderBy('nama_aplikasi')->get(['id_aplikasi', 'nama_aplikasi']);
        $domains = DB::table('domain')->whereNull('deleted_at')->orderBy('nama_domain')->get(['id_domain', 'nama_domain']);
        $pics = DB::table('pic')->orderBy('nama_pic')->get(['id_pic', 'nama_pic']);

        return view('pages.alerts.alerts', compact(
            'stats',
            'serverAlerts',
            'applicationAlerts',
            'domainAlerts',
            'servers',
            'applications',
            'domains',
            'pics'
        ));
    }

    public function store(Request $request)
    {
        DB::table('alert')->insert($this->alertPayload($request));

        return redirect()->route('alerts.alerts')->with('success', 'Alert berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        abort_unless(DB::table('alert')->where('id_alert', $id)->exists(), 404);

        DB::table('alert')->where('id_alert', $id)->update($this->alertPayload($request, true));

        return redirect()->route('alerts.alerts')->with('success', 'Alert berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $deleted = DB::table('alert')->where('id_alert', $id)->delete();
        abort_unless($deleted, 404);

        return redirect()->route('alerts.alerts')->with('success', 'Alert berhasil dihapus.');
    }

    public function clear()
    {
        $deleted = DB::table('alert')->delete();

        return redirect()->route('alerts.alerts')->with('success', "Berhasil menghapus {$deleted} alert.");
    }

    private function alertPayload(Request $request, bool $allowInfo = false): array
    {
        $type = $request->validate([
            'target_type' => 'required|in:Server,Application,Domain',
        ])['target_type'];

        $target = [
            'Server' => ['server', 'id_server'],
            'Application' => ['aplikasi', 'id_aplikasi'],
            'Domain' => ['domain', 'id_domain'],
        ][$type];

        $validated = $request->validate([
            'target_id' => ['required', 'integer', Rule::exists($target[0], $target[1])],
            'level' => $allowInfo ? 'required|in:Critical,Warning,Info' : 'required|in:Critical,Warning',
            'deskripsi' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'nullable|date|after_or_equal:waktu_mulai',
            'status' => 'required|in:Aktif,Selesai',
            'id_pic' => ['nullable', 'integer', Rule::exists('pic', 'id_pic')],
        ]);

        return [
            'id_server' => $type === 'Server' ? $validated['target_id'] : null,
            'id_aplikasi' => $type === 'Application' ? $validated['target_id'] : null,
            'id_domain' => $type === 'Domain' ? $validated['target_id'] : null,
            'level' => $validated['level'],
            'deskripsi' => $validated['deskripsi'],
            'keterangan' => $validated['keterangan'] ?? null,
            'waktu_mulai' => Carbon::parse($validated['waktu_mulai'])->format('Y-m-d H:i:s'),
            'waktu_selesai' => ! empty($validated['waktu_selesai'])
                ? Carbon::parse($validated['waktu_selesai'])->format('Y-m-d H:i:s')
                : null,
            'status' => $validated['status'],
            'id_pic' => $validated['id_pic'] ?? null,
        ];
    }
}