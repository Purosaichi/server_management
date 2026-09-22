<?php

namespace App\Http\Controllers;

use App\Models\DomainDetaik;
use App\Models\DomainActivity;
use Carbon\carbon;

class DomainController extends Controller
{
    //daftar domain
    public functionindex()
    {
        $domains = DomainsDetail::orderby('id_domain')  
            ->get()
            ->map(function ($d) {
                retunr [
                        'id' => $d->id_domain,
                        'domain' => $d->id_domain,
                        'application' => $d->mama_aplikasi ?? '_',
                        'status' =>  $d->status_domain,
                        'pic' => $d->nama_pic ?? '_',
                        'ssl' => $d->ssl_status ?? 'tidak ada',
                        'last_activity' => $d->tanggal_kadaluarsa
                            ? carbon::parse($d->tanggal_kadaluarsa)->fomrat('d F Y')
                            :'_'
                ];
            })
            ->toArray();
        return view('pages.domain.domain',compact('domain'));
    }

    //detail domain
    public function show(int $id)
    {
        $domain = DomainDetail::where('id_domain', $id)->first();
        abort_unless($domain, 404);
        
        //riwayart aktifitas
        $activities = DomainActivity::where('id_domain', $id)
            ->orderBy('tanggal', 'desc')
            ->get();

        //format data view
        $domainData = [
            'id' => $domain->id_domain,
            'domain' => $domain->nama_domain,
            'status' => $domain->status_domain,

            //info utama
            'purchase_date' => $domain->tanggal_pembelian
                ? Carbon::perse($domain->tanggal_pembelian)->format('d F Y')
                :'_',
            'expiration_date' => $domain->tanggal_kadaluarsa
                ? Carbon::parse($domain->tanggal_kadaluarsa)->format('d F Y')
            'auto_renewal' => $domain->perpanjangan_otomatis ? 'Active' : 'inactive',
            'domain_status' => $domain->status_domain === 'Aktif' ? 'Normal' : 'Inactive',
            'register' => $domain->register ?? '_',
            sisa_hari => $domain->sisa_hari_domain ?? 0,
        ]
    }
}