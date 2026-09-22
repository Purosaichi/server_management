<?php

namespace App\Http\Controllers;

use App\Models\License;
use Carbon\Carbon;

class LicenseController extends Controller
{
    public function index()
    {
        $licenses = License::all()->map(function ($lic) {
            $sisaHari = $lic->tanggal_kadaluarsa
                ? Carbon::now()->diffInDays(Carbon::parse($lic->tanggal_kadaluarsa), false)
                : null;

            return [
                'id'           => $lic->id_lisensi,
                'name'         => $lic->nama_lisensi,
                'provider'     => $lic->provider,
                'status'       => $lic->status,
                'status_type'  => $this->getStatusType($lic->status),
                'jumlah'       => $lic->jumlah_lisensi . ' License',
                'expired'      => $lic->tanggal_kadaluarsa
                                    ? Carbon::parse($lic->tanggal_kadaluarsa)->format('d M Y')
                                    : '-',
                'expired_note' => $this->getExpiredNote($sisaHari),
                'expired_type' => $this->getExpiredType($sisaHari),
                'logo'         => $lic->logo,
            ];
        });

        return view('pages.licenses.licenses', compact('licenses'));
    }

    public function show(int $id)
    {
        $lic = License::findOrFail($id);

        $sisaHari = $lic->tanggal_kadaluarsa
            ? Carbon::now()->diffInDays(Carbon::parse($lic->tanggal_kadaluarsa), false)
            : null;

        $license = [
            'id'           => $lic->id_lisensi,
            'name'         => $lic->nama_lisensi,
            'provider'     => $lic->provider,
            'status'       => $lic->status,
            'status_type'  => $this->getStatusType($lic->status),
            'jumlah'       => $lic->jumlah_lisensi . ' License',
            'expired'      => $lic->tanggal_kadaluarsa
                                ? Carbon::parse($lic->tanggal_kadaluarsa)->format('d M Y')
                                : '-',
            'expired_note' => $this->getExpiredNote($sisaHari),
            'expired_type' => $this->getExpiredType($sisaHari),
            'logo'         => $lic->logo,

            // Data detail
            'start_date'     => $lic->tanggal_mulai
                                ? Carbon::parse($lic->tanggal_mulai)->format('d F Y')
                                : '-',
            'license_number' => $lic->nomor_lisensi,
            'license_type'   => $lic->jenis_lisensi,
            'last_payment'   => '-',
            'next_due_date'  => $lic->tanggal_kadaluarsa
                                ? Carbon::parse($lic->tanggal_kadaluarsa)->format('d F Y')
                                : '-',
            'amount'         => '-',
            'payment_method' => '-',
            'asset_name'     => '-',
            'hostname'       => '-',
            'ip_address'     => '-',
            'location'       => '-',
            'pic'            => '-',
        ];

        return view('pages.licenses.detail', compact('license'));
    }

    private function getStatusType($status)
    {
        if ($status === 'Aman') return 'safe';
        if ($status === 'Akan Expired') return 'warning';
        return 'expired';
    }

    private function getExpiredNote($sisaHari)
    {
        if ($sisaHari === null) return '';
        if ($sisaHari < 0) return '(Sudah Expired)';
        return "({$sisaHari} Hari Lagi)";
    }

    private function getExpiredType($sisaHari)
    {
        if ($sisaHari === null) return 'safe';
        if ($sisaHari < 0) return 'expired';
        if ($sisaHari <= 60) return 'warning';
        return 'safe';
    }
}