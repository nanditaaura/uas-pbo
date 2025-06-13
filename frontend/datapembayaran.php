<?php
date_default_timezone_set('Asia/Jakarta');

$api_url = 'http://127.0.0.1:5000/pembayaran';
$data = [];
$error = null;

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
    <title>Data Pembayaran Zakat</title>
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
        /* Table Header */
        .table-header {
            background: linear-gradient(135deg, #fce7f3, #f9a8d4);
            color: #be185d;
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
                <h1 class="text-3xl font-bold text-pink-800">🕌 Pembayaran Zakat</h1>
            </div>
            <div class="space-x-3">
                <a href="dashboard.php" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 hover-scale">🏠 Kembali</a>
                <button onclick="alert('Fitur Generate Excel belum diimplementasikan')" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 hover-scale">Generate Excel</button>
            </div>
        </div>

        <!-- Error Alert -->
        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 border border-red-300 rounded-lg p-4 mb-6 fade-in">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Success/Error Alert for CRUD -->
        <div id="alert" class="hidden bg-pink-100 text-pink-700 border border-pink-300 rounded-lg p-4 mb-6 fade-in"></div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-lg border border-pink-200 bg-white">
            <table class="min-w-full text-sm text-center">
                <thead class="table-header uppercase text-xs font-bold">
                    <tr>
                        <th class="px-3 py-3">ID</th>
                        <th class="px-3 py-3">Jumlah Jiwa</th>
                        <th class="px-3 py-3">Jenis Zakat</th>
                        <th class="px-3 py-3">Nama</th>
                        <th class="px-3 py-3">Metode Pembayaran</th>
                        <th class="px-3 py-3">Total Bayar</th>
                        <th class="px-3 py-3">Nominal Dibayar</th>
                        <th class="px-3 py-3">Kembalian</th>
                        <th class="px-3 py-3">Keterangan</th>
                        <th class="px-3 py-3">Tanggal Bayar</th>
                        <th class="px-3 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white" id="pembayaran-table">
                    <?php if (!empty($data) && is_array($data)): ?>
                        <?php foreach ($data as $row): ?>
                            <tr class="border-b hover:bg-pink-50 transition-colors">
                                <td class="py-3"><?= htmlspecialchars($row['id']) ?></td>
                                <td class="py-3"><?= htmlspecialchars($row['jumlah_jiwa']) ?></td>
                                <td class="py-3"><?= htmlspecialchars($row['jenis_zakat']) ?></td>
                                <td class="py-3"><?= htmlspecialchars($row['nama']) ?></td>
                                <td class="py-3"><?= htmlspecialchars($row['metode_pembayaran']) ?></td>
                                <td class="py-3">Rp <?= number_format($row['total_bayar'], 2, ',', '.') ?></td>
                                <td class="py-3">Rp <?= number_format($row['nominal_dibayar'], 2, ',', '.') ?></td>
                                <td class="py-3">Rp <?= number_format($row['kembalian'], 2, ',', '.') ?></td>
                                <td class="py-3"><?= htmlspecialchars($row['keterangan'] ?? '-') ?></td>
                                <td class="py-3"><?= date('D, d M Y H:i', strtotime($row['tanggal_bayar'])) ?></td>
                                <td class="py-3">
                                    <button onclick="openEditModal(<?= $row['id'] ?>, '<?= htmlspecialchars($row['nama']) ?>', <?= $row['jumlah_jiwa'] ?>, '<?= $row['jenis_zakat'] ?>', '<?= $row['metode_pembayaran'] ?>', <?= $row['total_bayar'] ?>, <?= $row['nominal_dibayar'] ?>, <?= $row['kembalian'] ?>, '<?= htmlspecialchars($row['keterangan'] ?? '') ?>')" class="action-btn bg-pink-600 hover:bg-pink-700 text-white">Edit</button>
                                    <button onclick="deletePembayaran(<?= $row['id'] ?>)" class="action-btn bg-red-500 hover:bg-red-600 text-white">Hapus</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="11" class="py-4 text-gray-500">Tidak ada data pembayaran tersedia</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 shadow-2xl w-full max-w-lg fade-in">
            <h2 class="text-xl font-bold mb-4 text-center text-pink-800">Edit Pembayaran Zakat</h2>
            <form id="editForm" class="space-y-4">
                <input type="hidden" id="edit_id" name="id">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="edit_nama" class="block text-sm font-medium text-pink-700">Nama</label>
                        <input type="text" id="edit_nama" name="nama" class="mt-1 w-full p-3 border border-pink-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                    </div>
                    <div>
                        <label for="edit_jumlah_jiwa" class="block text-sm font-medium text-pink-700">Jumlah Jiwa</label>
                        <input type="number" id="edit_jumlah_jiwa" name="jumlah_jiwa" class="mt-1 w-full p-3 border border-pink-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                    </div>
                    <div>
                        <label for="edit_jenis_zakat" class="block text-sm font-medium text-pink-700">Jenis Zakat</label>
                        <select id="edit_jenis_zakat" name="jenis_zakat" class="mt-1 w-full p-3 border border-pink-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                            <option value="Zakat Fitrah">Zakat Fitrah</option>
                            <option value="Zakat Mal">Zakat Mal</option>
                        </select>
                    </div>
                    <div>
                        <label for="edit_metode_pembayaran" class="block text-sm font-medium text-pink-700">Metode Pembayaran</label>
                        <select id="edit_metode_pembayaran" name="metode_pembayaran" class="mt-1 w-full p-3 border border-pink-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                            <option value="Tunai">Tunai</option>
                            <option value="Transfer">Transfer</option>
                        </select>
                    </div>
                    <div>
                        <label for="edit_total_bayar" class="block text-sm font-medium text-pink-700">Total Bayar (Rp)</label>
                        <input type="number" id="edit_total_bayar" name="total_bayar" class="mt-1 w-full p-3 border border-pink-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" step="0.01" required>
                    </div>
                    <div>
                        <label for="edit_nominal_dibayar" class="block text-sm font-medium text-pink-700">Nominal Dibayar (Rp)</label>
                        <input type="number" id="edit_nominal_dibayar" name="nominal_dibayar" class="mt-1 w-full p-3 border border-pink-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" step="0.01" required>
                    </div>
                    <div>
                        <label for="edit_kembalian" class="block text-sm font-medium text-pink-700">Kembalian (Rp)</label>
                        <input type="number" id="edit_kembalian" name="kembalian" class="mt-1 w-full p-3 border border-pink-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" step="0.01" required>
                    </div>
                    <div>
                        <label for="edit_keterangan" class="block text-sm font-medium text-pink-700">Keterangan</label>
                        <input type="text" id="edit_keterangan" name="keterangan" class="mt-1 w-full p-3 border border-pink-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    </div>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 hover-scale">Simpan</button>
                    <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 hover-scale">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Show alert
        function showAlert(message, type) {
            const alert = document.getElementById('alert');
            alert.textContent = message;
            alert.className = border p-4 mb-6 fade-in ${type === 'error' ? 'bg-red-100 text-red-700 border-red-300' : 'bg-pink-100 text-pink-700 border-pink-300'};
            alert.classList.remove('hidden');
            setTimeout(() => alert.classList.add('hidden'), 5000);
        }

        // Open edit modal
        function openEditModal(id, nama, jumlah_jiwa, jenis_zakat, metode_pembayaran, total_bayar, nominal_dibayar, kembalian, keterangan) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_jumlah_jiwa').value = jumlah_jiwa;
            document.getElementById('edit_jenis_zakat').value = jenis_zakat;
            document.getElementById('edit_metode_pembayaran').value = metode_pembayaran;
            document.getElementById('edit_total_bayar').value = total_bayar;
            document.getElementById('edit_nominal_dibayar').value = nominal_dibayar;
            document.getElementById('edit_kembalian').value = kembalian;
            document.getElementById('edit_keterangan').value = keterangan;
            document.getElementById('editModal').classList.remove('hidden');
        }

        // Close edit modal
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Submit edit form
        document.getElementById('editForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);
            data.jumlah_jiwa = parseInt(data.jumlah_jiwa);
            data.total_bayar = parseFloat(data.total_bayar);
            data.nominal_dibayar = parseFloat(data.nominal_dibayar);
            data.kembalian = parseFloat(data.kembalian);
            data.tanggal_bayar = new Date().toISOString().slice(0, 19).replace('T', ' ');

            try {
                const response = await fetch(/pembayaran/${data.id}, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                if (!response.ok) throw new Error(result.error || 'Gagal mengupdate data');
                showAlert('Pembayaran berhasil diupdate!', 'success');
                closeEditModal();
                location.reload();
            } catch (error) {
                showAlert('Terjadi kesalahan: ' + error.message, 'error');
            }
        });

        // Delete pembayaran
        async function deletePembayaran(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) return;
            try {
                const response = await fetch(/pembayaran/${id}, {
                    method: 'DELETE'
                });
                const result = await response.json();
                if (!response.ok) throw new Error(result.error || 'Gagal menghapus data');
                showAlert('Pembayaran berhasil dihapus!', 'success');
                location.reload();
            } catch (error) {
                showAlert('Terjadi kesalahan: ' + error.message, 'error');
            }
        }
    </script>
</body>
</html>