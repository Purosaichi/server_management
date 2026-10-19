<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\DomainActivity;
use App\Models\DomainDetail;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DomainController extends Controller
{
    public function index()
    {
        try {
            $domains = DomainDetail::orderBy('id_domain')
                ->get()
                ->map(function ($d) {
                    return [
                        'id' => $d->id_domain,
                        'domain' => $d->nama_domain ?? $d->id_domain,
                        'application' => $d->nama_aplikasi ?? '_',
                        'status' => $d->status_domain ?? '-',
                        'pic' => $d->nama_pic ?? '_',
                        'ssl' => $d->ssl_status ?? 'tidak ada',
                        'last_activity' => $d->tanggal_kadaluarsa
                            ? Carbon::parse($d->tanggal_kadaluarsa)->format('d F Y')
                            : '_',
                    ];
                })
                ->toArray();
        } catch (QueryException $e) {
            $domains = [];
        }

        return view('pages.domain.domain', compact('domains'));
    }

    public function show(int $id)
    {
        try {
            $domain = DomainDetail::where('id_domain', $id)->first();
            if (! $domain) {
                abort(404);
            }

            $activities = DomainActivity::where('id_domain', $id)
                ->orderBy('tanggal', 'desc')
                ->get();

            $domainData = [
                'id' => $domain->id_domain,
                'domain' => $domain->nama_domain,
                'status' => $domain->status_domain,
                'purchase_date' => $domain->tanggal_pembelian
                    ? Carbon::parse($domain->tanggal_pembelian)->format('d F Y')
                    : '-',
                'expiration_date' => $domain->tanggal_kadaluarsa
                    ? Carbon::parse($domain->tanggal_kadaluarsa)->format('d F Y')
                    : '-',
                'auto_renewal' => $domain->perpanjangan_otomatis ? 'Active' : 'inactive',
                'domain_status' => $domain->status_domain === 'Aktif' ? 'Normal' : 'Inactive',
                'register' => $domain->registrar ?? '-',
                'sisa_hari' => $domain->sisa_hari_domain ?? 0,
                'pic_name' => $domain->nama_pic ?? '-',
                'pic_jabatan' => $domain->pic_jabatan ?? '-',
                'pic_email' => $domain->pic_email ?? '-',
                'pic_telepon' => $domain->pic_telepon ?? '-',
                'pic_divisi' => $domain->pic_divisi ?? '-',
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
                'reminders' => $this->buildReminders($domain),
                'activity_history' => $activities->map(function ($a) {
                    return [
                        'tanggal' => Carbon::parse($a->tanggal)->format('d F Y'),
                        'aktifitas' => $a->aktivitas,
                        'pic' => $a->nama_pic ?? '-',
                        'status' => $a->status ?? '-',
                        'catatan' => $a->catatan ?? '-',
                    ];
                })->toArray(),
            ];
        } catch (QueryException $e) {
            $domainData = [
                'domain' => 'Domain belum tersedia',
                'status' => '-',
                'purchase_date' => '-',
                'expiration_date' => '-',
                'auto_renewal' => 'inactive',
                'domain_status' => 'Inactive',
                'pic_name' => '-',
                'pic_jabatan' => '-',
                'pic_email' => '-',
                'pic_telepon' => '-',
                'pic_divisi' => '-',
                'ssl_provider' => '-',
                'ssl_status' => '-',
                'ssl_issued_date' => '-',
                'ssl_expiration_date' => '-',
                'ssl_certificate_type' => '-',
                'billing_status' => 'Unpaid',
                'billing_cycle' => '-',
                'last_payment' => '-',
                'next_due_date' => '-',
                'amount' => '-',
                'payment_method' => '-',
                'reminders' => [],
                'activity_history' => [],
            ];
        }

        return view('pages.domain.detail', ['domain' => $domainData]);
    }

    private function buildReminders($domain): array
    {
        $reminders = [];

        if ($domain->sisa_hari_domain !== null && $domain->sisa_hari_domain <= 60) {
            $reminders[] = [
                'type' => 'warning',
                'title' => 'Domain akan expired',
                'description' => $domain->nama_domain.' akan expired dalam '.$domain->sisa_hari_domain.' hari',
                'date' => Carbon::parse($domain->tanggal_kadaluarsa)->format('d M Y'),
            ];
        }

        if ($domain->ssl_tanggal_kadaluarsa) {
            $sisaSSL = Carbon::now()->diffInDays(Carbon::parse($domain->ssl_tanggal_kadaluarsa), false);
            if ($sisaSSL <= 60) {
                $reminders[] = [
                    'type' => 'info',
                    'title' => 'SSL Certificate akan expired',
                    'description' => 'Sertifikat SSL akan expired dalam '.round($sisaSSL).' hari',
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

    public function create()
    {
        $pics = DB::table('pic')->orderBy('nama_pic')->pluck('nama_pic', 'id_pic');

        return view('pages.domain.create', compact('pics'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_domain' => 'required|string|max:150|unique:domain,nama_domain',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'registrar' => 'nullable|string|max:100',
            'tanggal_pembelian' => 'nullable|date',
            'tanggal_kadaluarsa' => 'nullable|date|after_or_equal:tanggal_pembelian',
            'perpanjangan_otomatis' => 'boolean',
            'id_pic' => 'nullable|integer|exists:pic,id_pic',
            'ssl_provider' => 'nullable|string|max:100',
            'ssl_status' => 'nullable|in:Valid,Expired,Pending',
            'tanggal_ssl_terbit' => 'nullable|date',
            'ssl_tanggal_kadaluarsa' => 'nullable|date|after_or_equal:tanggal_ssl_terbit',
            'ssl_tipe' => 'nullable|string|max:100',
            'billing_status' => 'nullable|in:Paid,Unpaid',
            'billing_cycle' => 'nullable|string|max:50',
            'last_payment' => 'nullable|date',
            'next_due_date' => 'nullable|date',
            'amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($request, $validated) {
            $domain = Domain::create($this->domainAttributes($validated, $request));
            $this->saveSslCertificate($domain->id_domain, $validated);
            $this->saveBilling($domain->id_domain, $validated);
        });

        return redirect()->route('domain.domain')
            ->with('success', 'Domain berhasil ditambahkan!');
    }

    public function edit(int $id)
    {
        $domain = Domain::findOrFail($id);
        $pics = DB::table('pic')->orderBy('nama_pic')->pluck('nama_pic', 'id_pic');
        $sslCertificate = DB::table('ssl_certificate')
            ->where('id_domain', $id)
            ->orderByDesc('tanggal_kadaluarsa')
            ->first();
        $billing = DB::table('tagihan')
            ->where('id_domain', $id)
            ->where('jenis_tagihan', 'Domain')
            ->orderByDesc('tanggal_jatuh_tempo')
            ->first();

        return view('pages.domain.edit', compact('domain', 'pics', 'sslCertificate', 'billing'));
    }

    public function update(Request $request, int $id)
    {
        $domain = Domain::findOrFail($id);

        $validated = $request->validate([
            'nama_domain' => 'required|string|max:150|unique:domain,nama_domain,'.$id.',id_domain',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'registrar' => 'nullable|string|max:100',
            'tanggal_pembelian' => 'nullable|date',
            'tanggal_kadaluarsa' => 'nullable|date|after_or_equal:tanggal_pembelian',
            'perpanjangan_otomatis' => 'boolean',
            'id_pic' => 'nullable|integer|exists:pic,id_pic',
            'ssl_provider' => 'nullable|string|max:100',
            'ssl_status' => 'nullable|in:Valid,Expired,Pending',
            'tanggal_ssl_terbit' => 'nullable|date',
            'ssl_tanggal_kadaluarsa' => 'nullable|date|after_or_equal:tanggal_ssl_terbit',
            'ssl_tipe' => 'nullable|string|max:100',
            'billing_status' => 'nullable|in:Paid,Unpaid',
            'billing_cycle' => 'nullable|string|max:50',
            'last_payment' => 'nullable|date',
            'next_due_date' => 'nullable|date',
            'amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($request, $domain, $validated) {
            $domain->update($this->domainAttributes($validated, $request));
            $this->saveSslCertificate($domain->id_domain, $validated);
            $this->saveBilling($domain->id_domain, $validated);
        });

        return redirect()->route('domain.domain')
            ->with('success', 'Domain berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        $domain = Domain::findOrFail($id);
        $domain->delete();

        return redirect()->route('domain.domain')
            ->with('success', 'Domain berhasil dihapus!');
    }

    private function domainAttributes(array $validated, Request $request): array
    {
        return [
            'nama_domain' => $validated['nama_domain'],
            'status' => $validated['status'],
            'registrar' => $validated['registrar'] ?? null,
            'tanggal_pembelian' => $validated['tanggal_pembelian'] ?? null,
            'tanggal_kadaluarsa' => $validated['tanggal_kadaluarsa'] ?? null,
            'perpanjangan_otomatis' => $request->boolean('perpanjangan_otomatis'),
            'id_pic' => $validated['id_pic'] ?? null,
        ];
    }

    private function saveSslCertificate(int $domainId, array $validated): void
    {
        $fields = [
            'ssl_provider',
            'ssl_status',
            'tanggal_ssl_terbit',
            'ssl_tanggal_kadaluarsa',
            'ssl_tipe',
        ];

        if (! $this->hasSubmittedValues($validated, $fields)) {
            return;
        }

        $certificate = [
            'provider' => $validated['ssl_provider'] ?? null,
            'status' => $validated['ssl_status'] ?? 'Valid',
            'tipe_sertifikat' => $validated['ssl_tipe'] ?? null,
            'tanggal_terbit' => $validated['tanggal_ssl_terbit'] ?? null,
            'tanggal_kadaluarsa' => $validated['ssl_tanggal_kadaluarsa'] ?? null,
        ];

        $existingId = DB::table('ssl_certificate')
            ->where('id_domain', $domainId)
            ->orderByDesc('tanggal_kadaluarsa')
            ->value('id_ssl');

        if ($existingId !== null) {
            DB::table('ssl_certificate')->where('id_ssl', $existingId)->update($certificate);

            return;
        }

        DB::table('ssl_certificate')->insert($certificate + ['id_domain' => $domainId]);
    }

    private function saveBilling(int $domainId, array $validated): void
    {
        $fields = [
            'billing_status',
            'billing_cycle',
            'last_payment',
            'next_due_date',
            'amount',
            'payment_method',
        ];

        if (! $this->hasSubmittedValues($validated, $fields)) {
            return;
        }

        $billing = [
            'status_pembayaran' => $validated['billing_status'] ?? 'Unpaid',
            'siklus_tagihan' => $validated['billing_cycle'] ?? null,
            'tanggal_bayar_terakhir' => $validated['last_payment'] ?? null,
            'tanggal_jatuh_tempo' => $validated['next_due_date'] ?? null,
            'jumlah' => $validated['amount'] ?? null,
            'metode_pembayaran' => $validated['payment_method'] ?? null,
        ];

        $existingId = DB::table('tagihan')
            ->where('id_domain', $domainId)
            ->where('jenis_tagihan', 'Domain')
            ->orderByDesc('tanggal_jatuh_tempo')
            ->value('id_tagihan');

        if ($existingId !== null) {
            DB::table('tagihan')->where('id_tagihan', $existingId)->update($billing);

            return;
        }

        DB::table('tagihan')->insert($billing + [
            'jenis_tagihan' => 'Domain',
            'id_domain' => $domainId,
        ]);
    }

    private function hasSubmittedValues(array $validated, array $fields): bool
    {
        foreach ($fields as $field) {
            if (isset($validated[$field]) && $validated[$field] !== '') {
                return true;
            }
        }

        return false;
    }
}
