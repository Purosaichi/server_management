<?php


namespace App\Http\Controllers;

use App\Models\License;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LicenseController extends Controller
{
    public function index()
    {
        $licenses = License::query()->orderBy('nama_lisensi')->get()->map(function ($lic) {
            $sisaHari = $this->daysUntilExpiration($lic->tanggal_kadaluarsa);

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
                'logo'         => $this->resolveLogo($lic->logo),
            ];
        });

        return view('pages.licenses.licenses', compact('licenses'));
    }

    public function show(int $id)
    {
        $lic = License::findOrFail($id);
        $billing = DB::table('tagihan')
            ->where('id_lisensi', $id)
            ->orderByDesc('id_tagihan')
            ->first();
        $asset = DB::table('lisensi_aset')
            ->join('aset', 'aset.id_aset', '=', 'lisensi_aset.id_aset')
            ->leftJoin('server', 'server.id_Aset', '=', 'aset.id_aset')
            ->where('lisensi_aset.id_lisensi', $id)
            ->orderByDesc('lisensi_aset.id_lisensi_aset')
            ->first([
                'aset.nama_aset',
                'server.hostname',
                'server.alamat_ip_produksi',
                'server.lokasi_rack',
            ]);
        $payments = DB::table('tagihan')
            ->where('id_lisensi', $id)
            ->orderByDesc('id_tagihan')
            ->get()
            ->map(function ($payment) {
                return [
                    'date' => $payment->tanggal_bayar_terakhir
                        ? Carbon::parse($payment->tanggal_bayar_terakhir)->format('d F Y')
                        : '-',
                    'amount' => $payment->jumlah !== null
                        ? number_format((float) $payment->jumlah, 0, ',', '.')
                        : '-',
                    'method' => $payment->metode_pembayaran ?? '-',
                    'status' => $payment->status_pembayaran ?? '-',
                ];
            });

        $sisaHari = $this->daysUntilExpiration($lic->tanggal_kadaluarsa);

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
            'logo'         => $this->resolveLogo($lic->logo),

            /* Data detail */
            'start_date'     => $lic->tanggal_mulai
                                ? Carbon::parse($lic->tanggal_mulai)->format('d F Y')
                                : '-',
            'license_number' => $lic->nomor_lisensi,
            'license_type'   => $lic->jenis_lisensi,
            'last_payment'   => $billing?->tanggal_bayar_terakhir
                                ? Carbon::parse($billing->tanggal_bayar_terakhir)->format('d F Y')
                                : '-',
            'next_due_date'  => $billing?->tanggal_jatuh_tempo
                                ? Carbon::parse($billing->tanggal_jatuh_tempo)->format('d F Y')
                                : ($lic->tanggal_kadaluarsa
                                    ? Carbon::parse($lic->tanggal_kadaluarsa)->format('d F Y')
                                    : '-'),
            'billing_period' => $billing->siklus_tagihan ?? '-',
            'billing_status' => $billing->status_pembayaran ?? '-',
            'amount'         => $billing?->jumlah !== null
                                ? number_format((float) $billing->jumlah, 0, ',', '.')
                                : '-',
            'payment_method' => $billing->metode_pembayaran ?? '-',
            'asset_name'     => $asset->nama_aset ?? '-',
            'hostname'       => $asset->hostname ?? '-',
            'ip_address'     => $asset->alamat_ip_produksi ?? '-',
            'location'       => $asset->lokasi_rack ?? '-',
            'pic'            => '-',
        ];

        return view('pages.licenses.detail', compact('license', 'payments'));
    }

    public function create()
    {
        $license = null;
        $billing = null;
        $assets = DB::table('aset')->orderBy('nama_aset')->get(['id_aset', 'nama_aset']);
        $selectedAssetId = null;

        return view('pages.licenses.form', compact('license', 'billing', 'assets', 'selectedAssetId'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateLicense($request);
        $logo = $request->hasFile('logo') ? $this->storeLogo($request) : null;

        $license = DB::transaction(function () use ($validated, $logo) {
            $license = License::create($this->licenseAttributes($validated, $logo));
            $this->saveBilling($license->id_lisensi, $validated);
            $this->saveAsset($license->id_lisensi, $validated['id_aset'] ?? null);

            return $license;
        });

        return redirect()->route('licenses.detail', $license->id_lisensi)
            ->with('success', 'Lisensi berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $license = License::findOrFail($id);
        $billing = DB::table('tagihan')
            ->where('id_lisensi', $id)
            ->orderByDesc('id_tagihan')
            ->first();
        $assets = DB::table('aset')->orderBy('nama_aset')->get(['id_aset', 'nama_aset']);
        $selectedAssetId = DB::table('lisensi_aset')
            ->where('id_lisensi', $id)
            ->orderByDesc('id_lisensi_aset')
            ->value('id_aset');

        return view('pages.licenses.form', compact('license', 'billing', 'assets', 'selectedAssetId'));
    }

    public function update(Request $request, int $id)
    {
        $license = License::findOrFail($id);
        $validated = $this->validateLicense($request, $id);
        $logo = $request->hasFile('logo') ? $this->storeLogo($request) : $license->logo;

        DB::transaction(function () use ($license, $validated, $logo) {
            $license->update($this->licenseAttributes($validated, $logo));
            $this->saveBilling($license->id_lisensi, $validated);
            $this->saveAsset($license->id_lisensi, $validated['id_aset'] ?? null);
        });

        return redirect()->route('licenses.detail', $license->id_lisensi)
            ->with('success', 'Lisensi berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        License::findOrFail($id)->delete();

        return redirect()->route('licenses.licenses')
            ->with('success', 'Lisensi berhasil dihapus.');
    }

    private function validateLicense(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'nama_lisensi' => 'required|string|max:150',
            'provider' => 'required|string|max:100',
            'status' => 'required|in:Aman,Akan Expired,Expired',
            'jumlah_lisensi' => 'required|integer|min:1',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_kadaluarsa' => 'nullable|date|after_or_equal:tanggal_mulai',
            'nomor_lisensi' => 'nullable|string|max:150',
            'jenis_lisensi' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'billing_status' => 'nullable|in:Paid,Unpaid',
            'billing_period' => 'nullable|string|max:50',
            'last_payment' => 'nullable|date',
            'next_due_date' => 'nullable|date',
            'amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:100',
            'id_aset' => 'nullable|integer|exists:aset,id_aset',
        ]);
    }

    private function licenseAttributes(array $validated, ?string $logo): array
    {
        return [
            'nama_lisensi' => $validated['nama_lisensi'],
            'provider' => $validated['provider'],
            'status' => $validated['status'],
            'jumlah_lisensi' => $validated['jumlah_lisensi'],
            'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
            'tanggal_kadaluarsa' => $validated['tanggal_kadaluarsa'] ?? null,
            'nomor_lisensi' => $validated['nomor_lisensi'] ?? null,
            'jenis_lisensi' => $validated['jenis_lisensi'] ?? null,
            'logo' => $logo,
        ];
    }

    private function storeLogo(Request $request): string
    {
        $file = $request->file('logo');
        $directory = public_path('images/licenses');
        File::ensureDirectoryExists($directory);
        $filename = Str::uuid().'.'.$file->extension();
        $file->move($directory, $filename);

        return 'licenses/'.$filename;
    }

    private function saveBilling(int $licenseId, array $validated): void
    {
        $billingFields = [
            'billing_status',
            'billing_period',
            'last_payment',
            'next_due_date',
            'amount',
            'payment_method',
        ];

        $hasBilling = false;
        foreach ($billingFields as $field) {
            if (filled($validated[$field] ?? null)) {
                $hasBilling = true;
                break;
            }
        }

        if (empty($hasBilling)) {
            return;
        }

        $attributes = [
            'status_pembayaran' => $validated['billing_status'] ?? 'Unpaid',
            'siklus_tagihan' => $validated['billing_period'] ?? null,
            'tanggal_bayar_terakhir' => $validated['last_payment'] ?? null,
            'tanggal_jatuh_tempo' => $validated['next_due_date'] ?? null,
            'jumlah' => $validated['amount'] ?? null,
            'metode_pembayaran' => $validated['payment_method'] ?? null,
        ];
        $existing = DB::table('tagihan')
            ->where('id_lisensi', $licenseId)
            ->orderByDesc('id_tagihan')
            ->first(['id_tagihan']);

        if ($existing) {
            DB::table('tagihan')->where('id_tagihan', $existing->id_tagihan)->update($attributes);

            return;
        }

        DB::table('tagihan')->insert($attributes + [
            'jenis_tagihan' => 'Lisensi',
            'id_lisensi' => $licenseId,
        ]);
    }

    private function saveAsset(int $licenseId, mixed $assetId): void
    {
        $existing = DB::table('lisensi_aset')
            ->where('id_lisensi', $licenseId)
            ->orderByDesc('id_lisensi_aset')
            ->first(['id_lisensi_aset']);

        if (! $assetId) {
            if ($existing) {
                DB::table('lisensi_aset')
                    ->where('id_lisensi_aset', $existing->id_lisensi_aset)
                    ->delete();
            }

            return;
        }

        if ($existing) {
            DB::table('lisensi_aset')
                ->where('id_lisensi_aset', $existing->id_lisensi_aset)
                ->update(['id_aset' => $assetId]);

            return;
        }

        DB::table('lisensi_aset')->insert([
            'id_lisensi' => $licenseId,
            'id_aset' => $assetId,
            'tanggal_pemasangan' => now()->toDateString(),
        ]);
    }

    private function getStatusType($status)
    {
        if ($status === 'Aman') return 'safe';
        if ($status === 'Akan Expired') return 'warning';
        return 'expired';
    }

    private function daysUntilExpiration(?string $expirationDate): ?int
    {
        if ($expirationDate === null) {
            return null;
        }

        return (int) Carbon::today()->diffInDays(
            Carbon::parse($expirationDate)->startOfDay(),
            false
        );
    }

    private function resolveLogo($logo)
    {
        $logoAliases = [
            'jetbrains.png' => 'IntelliJ_IDEA.png',
            'intuit.png' => 'quickbooks.png',
            'autodesk.png' => 'Auto_cad.png',
            'ubuntu.png' => 'ubuntu_pro.png',
            'elastic.png' => 'elastic_stack.png',
            'vmware.png' => 'VMware.png',
        ];

        return $logoAliases[$logo] ?? $logo;
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