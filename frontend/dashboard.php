<?php
date_default_timezone_set('Asia/Jakarta');

$api_url = 'http://127.0.0.1:5000/pembayaran';
$data = [];
$error = null;
$total_pembayaran = 0;
$jumlah_transaksi = 0;

$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response !== false && $http_code === 200) {
    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $error = 'Gagal mendekode data JSON: ' . json_last_error_msg();
    } else {
        $jumlah_transaksi = count($data);
        foreach ($data as $row) {
            $total_pembayaran += $row['nominal_dibayar'];
        }
    }
} else {
    $error = 'Gagal mengambil data pembayaran: ' . ($http_code ? "HTTP $http_code" : 'Koneksi gagal');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Zakat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        /* Custom Pink Gradient */
        .bg-pink-gradient {
            background: linear-gradient(to bottom, #fff0f5, #ffe4e1, #fbcfe8);
        }
        /* Hover Scale Effect */
        .hover-scale:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #fce7f3;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #ff69b4;
            border-radius: 4px;
        }
        /* Card Gradient */
        .card-gradient {
            background: linear-gradient(135deg, #ffffff, #fff0f5);
        }
    </style>
</head>
<body class="bg-pink-gradient min-h-screen flex items-center justify-center p-6">
    <!-- Main Container -->
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl p-8 fade-in custom-scrollbar">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center space-x-3">
                <svg class="w-10 h-10 text-pink-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10c1.82 0 3.53-.49 5-1.32l3.26 1.49a1 1 0 001.34-1.34l-1.49-3.26A9.96 9.96 0 0022 12c0-5.52-4.48-10-10-10zm0 18c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
                </svg>
                <h1 class="text-3xl font-bold text-pink-800">🕌 Dashboard Zakat</h1>
            </div>
            <div class="space-x-3">
                <a href="tambahpembayaran.php" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 hover-scale">Tambah Pembayaran</a>
                <a href="beras.php" class="bg-pink-400 text-white px-4 py-2 rounded-lg hover:bg-pink-500 hover-scale">Data Beras</a>
                <a href="datapembayaran.php" class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 hover-scale">Data Pembayaran</a>
            </div>
        </div>

        <!-- Error Alert -->
        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 border border-red-300 rounded-lg p-4 mb-6 fade-in">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="card card-gradient p-6 rounded-xl shadow-lg hover-scale">
                <h2 class="text-xl font-semibold text-pink-700 mb-2">Total Pembayaran</h2>
                <p class="text-3xl font-bold text-pink-600">Rp <?= number_format($total_pembayaran, 2, ',', '.') ?></p>
            </div>
            <div class="card card-gradient p-6 rounded-xl shadow-lg hover-scale">
                <h2 class="text-xl font-semibold text-pink-700 mb-2">Jumlah Transaksi</h2>
                <p class="text-3xl font-bold text-pink-500"><?= $jumlah_transaksi ?></p>
            </div>
            <div class="card card-gradient p-6 rounded-xl shadow-lg hover-scale">
                <h2 class="text-xl font-semibold text-pink-700 mb-2">Tanggal Terakhir Update</h2>
                <p class="text-3xl font-bold text-pink-400"><?= date('D, d M Y H:i') ?></p>
            </div>
        </div>
    </div>

    <script>
        // Optional: Add interactivity if needed (e.g., refresh data on click)
        document.addEventListener('DOMContentLoaded', () => {
            // Add fade-in effect to cards
            document.querySelectorAll('.card').forEach(card => {
                card.classList.add('fade-in');
            });
        });
    </script>
</body>
</html>