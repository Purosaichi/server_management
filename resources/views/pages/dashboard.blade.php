@extends('layouts.app')

@section('title', 'Server')

@section('page-title', 'Server')
@section('page-subtitle', 'Daftar semua server yang dimonitoring')

@section('content')
{{-- Tabel Server --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Server</h3>
        <span class="text-sm text-gray-500">Total: 15 server</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Server</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CPU</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RAM</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DISC</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Up Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @php
                    $servers = [
                        ['name' => 'SVR - 001', 'ip' => '103.231.3.01', 'status' => 'Online', 'cpu' => '50%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '20d 12h'],
                        ['name' => 'SVR - 002', 'ip' => '103.231.3.02', 'status' => 'Online', 'cpu' => '50%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '20d 12h'],
                        ['name' => 'SVR - 003', 'ip' => '103.231.3.03', 'status' => 'Online', 'cpu' => '50%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '20d 12h'],
                        ['name' => 'SVR - 004', 'ip' => '103.231.3.04', 'status' => 'Online', 'cpu' => '50%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '20d 12h'],
                        ['name' => 'SVR - 005', 'ip' => '103.231.3.05', 'status' => 'Offline', 'cpu' => '---', 'ram' => '---', 'disk' => '---', 'uptime' => '---'],
                        ['name' => 'SVR - 006', 'ip' => '103.231.3.06', 'status' => 'Online', 'cpu' => '50%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '20d 12h'],
                        ['name' => 'SVR - 007', 'ip' => '103.231.3.07', 'status' => 'Online', 'cpu' => '50%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '20d 12h'],
                        ['name' => 'SVR - 008', 'ip' => '103.231.3.08', 'status' => 'Online', 'cpu' => '50%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '20d 12h'],
                        ['name' => 'SVR - 009', 'ip' => '103.231.3.09', 'status' => 'Online', 'cpu' => '50%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '20d 12h'],
                        ['name' => 'SVR - 010', 'ip' => '103.231.3.10', 'status' => 'Offline', 'cpu' => '---', 'ram' => '---', 'disk' => '---', 'uptime' => '---'],
                        ['name' => 'SVR - 011', 'ip' => '103.231.3.11', 'status' => 'Online', 'cpu' => '50%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '20d 12h'],
                        ['name' => 'SVR - 012', 'ip' => '103.231.3.12', 'status' => 'Online', 'cpu' => '50%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '20d 12h'],
                        ['name' => 'SVR - 013', 'ip' => '103.231.3.13', 'status' => 'Online', 'cpu' => '50%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '20d 12h'],
                        ['name' => 'SVR - 014', 'ip' => '103.231.3.14', 'status' => 'Online', 'cpu' => '50%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '20d 12h'],
                        ['name' => 'SVR - 015', 'ip' => '103.231.3.15', 'status' => 'Online', 'cpu' => '50%', 'ram' => '70%', 'disk' => '90%', 'uptime' => '20d 12h'],
                    ];
                @endphp

                @foreach($servers as $server)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $server['name'] }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $server['ip'] }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full 
                            {{ $server['status'] == 'Online' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $server['status'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $server['cpu'] }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $server['ram'] }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $server['disk'] }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $server['uptime'] }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('server.detail', ['id' => $loop->index + 1]) }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                            Selengkapnya →
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection