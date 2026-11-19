<?php


namespace App\Http\Controllers;

use App\Models\License;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LicenseController extends Controller
{
    public function index()
    {
        $billingByLicense = DB::table('tagihan')
            ->whereNotNull('id_lisensi')
            ->orderByDesc('id_tagihan')
            ->get(['id_lisensi', 'tanggal_jatuh_tempo'])
            ->unique('id_lisensi')
            ->keyBy('id_lisensi');

        $licenses = License::query()->orderBy('nama_lisensi')->get()->map(function ($lic) use ($billingByLicense) {
            $latestBilling = $billingByLicense->get($lic->id_lisensi);
            $effectiveExpiry = $latestBilling?->tanggal_jatuh_tempo ?? $lic->tanggal_kadaluarsa;
            $sisaHari = $this->daysUntilExpiration($effectiveExpiry);
            $dynamicStatus = $this->syncDynamicStatus($lic, $effectiveExpiry);

            return [
                'id'           => $lic->id_lisensi,
                'name'         => $lic->nama_lisensi,
                'provider'     => $lic->provider,
                'status'       => $dynamicStatus['status'],
                'status_type'  => $dynamicStatus['type'],
                'jumlah'       => $lic->jumlah_lisensi . ' License',
                'expired'      => $effectiveExpiry
                                    ? Carbon::parse($effectiveExpiry)->format('d M Y')
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
                'aset.id_lokasi_aset',
                'server.hostname',
                'server.alamat_ip_produksi',
            ]);
        $assetLocation = $asset?->id_lokasi_aset
            ? DB::table('lokasi_aset')
                ->leftJoin('data_center', 'data_center.id_data_center', '=', 'lokasi_aset.id_data_center')
                ->where('lokasi_aset.id_lokasi_aset', $asset->id_lokasi_aset)
                ->whereNull('lokasi_aset.deleted_at')
                ->first([
                    'data_center.nama_data_center',
                    'lokasi_aset.nama_gedung',
                    'lokasi_aset.lantai',
                    'lokasi_aset.nama_ruangan',
                    'lokasi_aset.zona',
                    'lokasi_aset.kode_rak',
                    'lokasi_aset.nama_rak',
                ])
            : null;
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

        $effectiveExpiry = $billing?->tanggal_jatuh_tempo ?? $lic->tanggal_kadaluarsa;
        $sisaHari = $this->daysUntilExpiration($effectiveExpiry);
        $dynamicStatus = $this->syncDynamicStatus($lic, $effectiveExpiry);

        $license = [
            'id'           => $lic->id_lisensi,
            'name'         => $lic->nama_lisensi,
            'provider'     => $lic->provider,
            'status'       => $dynamicStatus['status'],
            'status_type'  => $dynamicStatus['type'],
            'jumlah'       => $lic->jumlah_lisensi . ' License',
            'expired'      => $effectiveExpiry
                                ? Carbon::parse($effectiveExpiry)->format('d M Y')
                                : '-',
            'expired_note' => $this->getExpiredNote($sisaHari),
            'expired_type' => $this->getExpiredType($sisaHari),
            'logo'         => $this->resolveLogo($lic->logo),

            /* Data detail */
            'start_date'     => ($billing?->tanggal_bayar_terakhir ?? $lic->tanggal_mulai)
                                ? Carbon::parse($billing?->tanggal_bayar_terakhir ?? $lic->tanggal_mulai)->format('d F Y')
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
            'location'       => $assetLocation
                ? (collect([
                    $assetLocation->nama_data_center,
                    $assetLocation->nama_gedung,
                    $assetLocation->lantai,
                    $assetLocation->nama_ruangan,
                    $assetLocation->zona,
                    $assetLocation->kode_rak,
                    $assetLocation->nama_rak,
                ])->filter()->implode(' - ') ?: '-')
                : '-',
            'pic'            => '-',
        ];

        $paymentMinDate = $effectiveExpiry
            ? Carbon::parse($effectiveExpiry)->toDateString()
            : null;
        $paymentDefaultDate = $paymentMinDate && now()->toDateString() < $paymentMinDate
            ? $paymentMinDate
            : now()->toDateString();

        return view('pages.licenses.detail', compact('license', 'payments', 'paymentMinDate', 'paymentDefaultDate'));
    }

    public function create()
    {
        $license = null;
        $billing = null;
        $assets = DB::table('aset')->orderBy('nama_aset')->get(['id_aset', 'nama_aset']);
        $selectedAssetId = null;
        ['billingStartDate' => $billingStartDate, 'billingEndDate' => $billingEndDate] = $this->formDateValues($billing, $license);

        return view('pages.licenses.form', compact('license', 'billing', 'assets', 'selectedAssetId', 'billingStartDate', 'billingEndDate'));
    }

    public function storePayment(Request $request, int $id)
    {
        $license = License::findOrFail($id);

        $billing = DB::table('tagihan')
            ->where('id_lisensi', $id)
            ->orderByDesc('id_tagihan')
            ->first();

        if (! $billing || $billing->jumlah === null) {
            return back()->withErrors([
                'payment_date' => 'Nominal pembayaran belum tersedia pada data billing lisensi.',
            ]);
        }

        $validated = $request->validate([
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:Transfer Bank,Kartu Kredit,Kartu Debit,E-Wallet,Virtual Account,Tunai',
        ]);

        $minimumPaymentDate = $billing->tanggal_jatuh_tempo ?? $license->tanggal_kadaluarsa;
        if ($minimumPaymentDate && Carbon::parse($validated['payment_date'])->lt(Carbon::parse($minimumPaymentDate))) {
            return back()->withErrors([
                'payment_date' => 'Tanggal pembayaran tidak boleh sebelum tanggal expired lisensi (' . Carbon::parse($minimumPaymentDate)->format('d F Y') . ').',
            ])->withInput();
        }

        $billingPeriod = $billing->siklus_tagihan;
        $dueDate = $billingPeriod
            ? $this->calculateDueDate($validated['payment_date'], $billingPeriod)
            : null;

        DB::table('tagihan')->insert([
            'jenis_tagihan' => 'Lisensi',
            'id_lisensi' => $id,
            'status_pembayaran' => 'Paid',
            'siklus_tagihan' => $billingPeriod,
            'tanggal_bayar_terakhir' => $validated['payment_date'],
            'tanggal_jatuh_tempo' => $dueDate,
            'jumlah' => $billing->jumlah,
            'metode_pembayaran' => $validated['payment_method'],
        ]);

        return redirect()->route('licenses.detail', $id)
            ->with('success', 'Pembayaran berhasil dicatat.');
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
        ['billingStartDate' => $billingStartDate, 'billingEndDate' => $billingEndDate] = $this->formDateValues($billing, $license);

        return view('pages.licenses.form', compact('license', 'billing', 'assets', 'selectedAssetId', 'billingStartDate', 'billingEndDate'));
    }

    public function update(Request $request, int $id)
    {
        $license = License::findOrFail($id);
        $validated = $this->validateLicense($request, $id);
        $logo = $request->hasFile('logo') ? $this->storeLogo($request) : $license->logo;

        DB::transaction(function () use ($license, $validated, $logo) {
            $license->update($this->licenseAttributes($validated, $logo, $license->status));
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
        $validated = $request->validate([
            'nama_lisensi' => 'required|string|max:150',
            'provider' => 'required|string|max:100',
            'jumlah_lisensi' => 'required|integer|min:1',
            'nomor_lisensi' => [
                'nullable',
                'string',
                'max:150',
                Rule::unique('lisensi', 'nomor_lisensi')->ignore($id, 'id_lisensi'),
            ],
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

        if (filled($validated['last_payment'] ?? null)
            && filled($validated['billing_period'] ?? null)) {
            $calculatedDueDate = $this->calculateDueDate(
                $validated['last_payment'],
                $validated['billing_period'],
            );

            if ($calculatedDueDate !== null) {
                $validated['next_due_date'] = $calculatedDueDate;
            }
        }

        return $validated;
    }

    private function licenseAttributes(array $validated, ?string $logo, string $fallbackStatus = 'Aman'): array
    {
        return [
            'nama_lisensi' => $validated['nama_lisensi'],
            'provider' => $validated['provider'],
            'status' => $this->getDynamicStatus($validated['next_due_date'] ?? null, $fallbackStatus)['status'],
            'jumlah_lisensi' => $validated['jumlah_lisensi'],
            'tanggal_mulai' => $validated['last_payment'] ?? null,
            'tanggal_kadaluarsa' => $validated['next_due_date'] ?? null,
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

    private function formDateValues(?object $billing, ?License $license): array
    {
        $start = $this->toDateInput($billing?->tanggal_bayar_terakhir ?? $license?->tanggal_mulai);
        $storedEnd = $this->toDateInput($billing?->tanggal_jatuh_tempo ?? $license?->tanggal_kadaluarsa);
        $period = $billing?->siklus_tagihan ?? null;
        $calculatedEnd = ($start !== '' && filled($period))
            ? $this->calculateDueDate($start, $period)
            : null;

        return [
            'billingStartDate' => $start,
            'billingEndDate' => $calculatedEnd ?? $storedEnd,
        ];
    }

    private function toDateInput(mixed $value): string
    {
        if (blank($value)) {
            return '';
        }

        return Carbon::parse($value)->toDateString();
    }

    private function calculateDueDate(string $startDate, string $billingPeriod): ?string
    {
        if (! preg_match('/^\s*(\d+)\s*(hari|days?|minggu|weeks?|bulan|months?|tahun|years?)?\s*$/i', $billingPeriod, $matches)) {
            return null;
        }

        $amount = (int) $matches[1];
        $unit = strtolower($matches[2] ?? 'tahun');
        $date = Carbon::parse($startDate);

        if (in_array($unit, ['hari', 'day', 'days'], true)) {
            $date->addDays($amount);
        } elseif (in_array($unit, ['minggu', 'week', 'weeks'], true)) {
            $date->addWeeks($amount);
        } elseif (in_array($unit, ['bulan', 'month', 'months'], true)) {
            $date->addMonthsNoOverflow($amount);
        } else {
            $date->addMonthsNoOverflow($amount * 12);
        }

        return $date->toDateString();
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

    private function getDynamicStatus(?string $expirationDate, ?string $fallback = null): array
    {
        if ($expirationDate === null) {
            return [
                'status' => $fallback ?? '-',
                'type' => $this->getStatusType($fallback),
            ];
        }

        $daysRemaining = $this->daysUntilExpiration($expirationDate);

        if ($daysRemaining < 0) {
            return ['status' => 'Expired', 'type' => 'expired'];
        }

        if ($daysRemaining <= 90) {
            return ['status' => 'Akan Expired', 'type' => 'warning'];
        }

        return ['status' => 'Aman', 'type' => 'safe'];
    }

    private function syncDynamicStatus(License $license, ?string $expirationDate): array
    {
        $dynamicStatus = $this->getDynamicStatus($expirationDate, $license->status);

        if ($license->status !== $dynamicStatus['status']) {
            DB::table('lisensi')
                ->where('id_lisensi', $license->id_lisensi)
                ->update(['status' => $dynamicStatus['status']]);

            $license->status = $dynamicStatus['status'];
        }

        return $dynamicStatus;
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
            'redhat.png' => 'Red-Hat.png',
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
