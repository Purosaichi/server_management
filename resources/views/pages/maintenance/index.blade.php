@extends('layouts.app')

@section('title', 'Maintenance - GTK Monitoring')
@section('page-title', 'Maintenance')
@section('page-subtitle', 'Daftar jadwal pemeliharaan sistem')

@section('content')


<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <!-- Total -->
    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
        <p class="text-gray-400 text-sm font-medium">Total Maintenance</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">8</p>
        <p class="text-xs text-gray-400">Periode ini (Agustus 2026)</p>
    </div>
    <!-- Completed -->
    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
        <p class="text-gray-400 text-sm font-medium">Maintenance Selesai</p>
        <p class="text-2xl font-bold text-green-600 mt-1">4</p>
        <p class="text-xs text-gray-400">50% dari total</p>
    </div>
    <!-- In Progress -->
    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
        <p class="text-gray-400 text-sm font-medium">In Progress</p>
        <p class="text-2xl font-bold text-blue-600 mt-1">2</p>
        <p class="text-xs text-gray-400">Sedang berlangsung</p>
    </div>
    <!-- Scheduled -->
    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
        <p class="text-gray-400 text-sm font-medium">Scheduled</p>
        <p class="text-2xl font-bold text-yellow-600 mt-1">2</p>
        <p class="text-xs text-gray-400">Akan datang</p>
    </div>
</div>

<!-- table maintenance -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Maintenance</h3>
        <div class="flex gap-2">
            <button class="px-3 py-1.5 text-xs font-medium rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 transition">Semua</button>
            <button class="px-3 py-1.5 text-xs font-medium rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 transition">Server</button>
            <button class="px-3 py-1.5 text-xs font-medium rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 transition">Application</button>
            <button class="px-3 py-1.5 text-xs font-medium rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 transition">Domain</button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600 font-medium">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Maintenance</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PIC</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>   
            </thead>
            <tbody class="divide-y divide-gray-200">
                @php
                    $maintenances = [
                        [
                            'id' => 'MTN-2026-001',
                            'target' => 'SVR-001',
                            'jenis' => 'Update & Patch',
                            'kategori' => 'Application Server',
                            'jadwal' => '20 Agu 2026',
                            'jam' => '09:00 - 10:00',
                            'durasi' => '1 Jam',
                            'pic' => 'Slowrance Stroll',
                            'status' => 'In Progress'
                        ],
                        [
                            'id' => 'MTN-2026-002',
                            'target' => 'GTK-Kemendikasmen',
                            'jenis' => 'Database Optimization',
                            'kategori' => 'Application',
                            'jadwal' => '22 Agu 2026',
                            'jam' => '09:00 - 10:00',
                            'durasi' => '1 Jam',
                            'pic' => 'Slowrance Stroll',
                            'status' => 'Scheduled'
                        ],
                        [
                            'id' => 'MTN-2026-004',
                            'target' => 'SVR-012',
                            'jenis' => 'Hardware Check',
                            'kategori' => 'Database Server',
                            'jadwal' => '10 Nov 2026',
                            'jam' => '13:00 - 15:00',
                            'durasi' => '2 Jam',
                            'pic' => 'Slowrance Stroll',
                            'status' => 'Scheduled'
                        ],
                        [
                            'id' => 'MTN-2026-005',
                            'target' => 'SVR-020',
                            'jenis' => 'Hardware Upgrade',
                            'kategori' => 'Database Server',
                            'jadwal' => '15 Nov 2026',
                            'jam' => '13:00 - 15:00',
                            'durasi' => '2 Jam',
                            'pic' => 'Slowrance Stroll',
                            'status' => 'Scheduled'
                        ],
                        [
                            'id' => 'MTN-2026-006',
                            'target' => 'SVR-004',
                            'jenis' => 'Server Cleanup',
                            'kategori' => 'Database Server',
                            'jadwal' => '18 Nov 2026',
                            'jam' => '13:00 - 15:00',
                            'durasi' => '2 Jam',
                            'pic' => 'Slowrance Stroll',
                            'status' => 'Scheduled'
                        ],
                    ];
                @endphp
                
                @foreach($maintenances as $mt)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $mt['id'] }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $mt['target'] }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $mt['jenis'] }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full
                            {{ $mt['kategori'] == 'Application Server' ? 'bg-purple-50 text-purple-700' : '' }}
                            {{ $mt['kategori'] == 'Application' ? 'bg-blue-50 text-blue-700' : '' }}
                            {{ $mt['kategori'] == 'Domain' ? 'bg-green-50 text-green-700' : '' }}
                            {{ $mt['kategori'] == 'Database Server' ? 'bg-orange-50 text-orange-700' : '' }}
                            ">
                            {{ $mt['kategori']}}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-gray-600">{{ $mt['jadwal'] }}</div>
                        <div class="text-xs text-gray-400">{{ $mt['jam'] }}</div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $mt['durasi'] }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $mt['pic'] }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full
                            {{ $mt['status'] == 'In Progress' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $mt['status'] == 'Completed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $mt['status'] == 'Scheduled' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            ">
                            {{ $mt['status'] }}
                        </span> 
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-3 border-t border-gray-100 text-sm text-gray-500">
        Menampilkan 6 dari 8 data
    </div>
</div>

@endsection

                   
                    
                        



