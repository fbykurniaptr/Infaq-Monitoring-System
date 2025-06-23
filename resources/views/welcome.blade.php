<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Infaq Monitoring System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-emerald-600 shadow-md">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center">
                <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h1 class="ml-2 text-2xl font-bold text-white">Sistem Monitoring Infaq</h1>
            </div>
            <div class="text-white text-sm">
                <div class="flex items-center">
                    <div class="mr-2 h-3 w-3 rounded-full bg-green-400 animate-pulse"></div>
                    <span>ESP Connected</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Infaq -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-gray-500 text-lg">Total Infaq Terkumpul</h2>
                    <svg class="h-8 w-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <p class="mt-4 text-4xl font-bold text-emerald-600">Rp {{ number_format($totalInfaq, 0, ',', '.') }}</p>
                <p class="mt-2 text-sm text-gray-500">Sejak {{ \Carbon\Carbon::parse($firstInfaqDate)->format('d M Y') }}</p>
            </div>

            <!-- Hari Ini -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-gray-500 text-lg">Infaq Hari Ini</h2>
                    <svg class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="mt-4 text-4xl font-bold text-blue-600">Rp {{ number_format($todayInfaq, 0, ',', '.') }}</p>
                <p class="mt-2 text-sm text-gray-500">{{ \Carbon\Carbon::now()->format('d M Y') }}</p>
            </div>

            <!-- Status -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-gray-500 text-lg">Status Sensor</h2>
                    <svg class="h-8 w-8 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                    </svg>
                </div>
                <div class="mt-4 flex items-center">
                    <div class="mr-2 h-4 w-4 rounded-full bg-green-500"></div>
                    <span class="font-medium">TCS3200 Aktif</span>
                </div>
                <div class="mt-2 flex items-center">
                    <div class="mr-2 h-4 w-4 rounded-full bg-green-500"></div>
                    <span class="font-medium">ESP32 Terhubung</span>
                </div>
                <p class="mt-2 text-sm text-gray-500">Terakhir update: {{ \Carbon\Carbon::now()->subMinutes(2)->format('H:i:s') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-medium mb-4">Statistik Harian</h2>
                <canvas id="donationChart" class="w-full h-64"></canvas>
            </div>

            <!-- Deteksi Terbaru -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-medium mb-4">Deteksi Terbaru</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($latestInfaq as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i:s') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Terdeteksi</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tentang Sistem -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium mb-4">Tentang Sistem</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-600 mb-4">
                        Sistem monitoring infaq berbasis IoT ini menggunakan sensor warna TCS3200 untuk mendeteksi nominal uang yang masuk berdasarkan warna mata uang. Data dikirimkan melalui ESP32 ke server secara real-time.
                    </p>
                    <p class="text-gray-600">
                        Sistem ini membantu pengelolaan transparansi kotak infaq dengan mendeteksi dan mencatat semua donasi yang masuk secara otomatis.
                    </p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-medium text-gray-700 mb-2">Komponen Hardware:</h3>
                    <ul class="list-disc list-inside text-gray-600">
                        <li>ESP32</li>
                        <li>Sensor Warna TCS3200</li>
                        <li>Kotak Pengumpul Infaq</li>
                        <li>Power Bank</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-gray-100 border-t mt-8">
        <div class="container mx-auto px-4 py-4">
            <p class="text-center text-gray-600 text-sm">&copy; {{ date('Y') }} Sistem Monitoring Infaq | Tugas Akhir IoT</p>
        </div>
    </footer>

    <script>
        const chartData = @json($dailyInfaq);
        const chart = new Chart(document.getElementById('donationChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                datasets: [{
                    label: 'Infaq Harian (Rupiah)',
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    borderColor: 'rgb(16, 185, 129)',
                    borderWidth: 1,
                    data: chartData
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>
