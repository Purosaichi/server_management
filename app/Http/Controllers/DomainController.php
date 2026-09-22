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
    }
}