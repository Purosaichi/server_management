<?php

namespace App\Http\Controllers;

use App\Models\DomainActivity;
use App\Models\DomainDetail;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

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
                'register' => $domain->register ?? '-',
                'sisa_hari' => $domain->sisa_hari_domain ?? 0,
                'pic_name' => $domain->nama_pic ?? '-',
                'pic_jabatan' => $domain->pic_jabatan ?? '-',
                'pic_email' => $domain->pic_email ?? '-',
                'pic_telepon' => $domain->pic_telepon ?? '-',
                'pic_divisi' => $domain->pic_divisi ?? '-',
                'ssl_provider' => $domain->ssl_provider ?? '-',
                'ssl_status' => $domain->ssl_status ?? '-',
                'ssl_issued_date' => $domain->tanggal_ssl_terbit
                    ? Carbon::parse($domain->tanggal_ssl_terbit)->format('d F Y')
                    : '-',
                'ssl_expiration_date' => $domain->ssl_tanggal_kadaluarsa
                    ? Carbon::parse($domain->ssl_tanggal_kadaluarsa)->format('d F Y')
                    : '-',
                'ssl_certificate_type' => $domain->ssl_tipe ?? '-',
                'billing_status' => $domain->billing_status ?? $domain->biling_status ?? 'Unpaid',
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
                'description' => $domain->nama_domain . ' akan expired dalam ' . $domain->sisa_hari_domain . ' hari',
                'date' => Carbon::parse($domain->tanggal_kadaluarsa)->format('d M Y'),
            ];
        }

        if ($domain->ssl_tanggal_kadaluarsa) {
            $sisaSSL = Carbon::now()->diffInDays(Carbon::parse($domain->ssl_tanggal_kadaluarsa), false);
            if ($sisaSSL <= 60) {
                $reminders[] = [
                    'type' => 'info',
                    'title' => 'SSL Certificate akan expired',
                    'description' => 'Sertifikat SSL akan expired dalam ' . round($sisaSSL) . ' hari',
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
}
