<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Data Fetch Result</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container-fluid mt-5">

        <?php
        // Ambil nilai bulan dan tahun dari request GET, jika tidak ada, gunakan bulan dan tahun saat ini.
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
        $month = isset($_GET['month']) ? str_pad($_GET['month'], 2, '0', STR_PAD_LEFT) : date('m');

        $months = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];
        ?>

        <form action="" method="GET" class="row g-3 align-items-end mb-4 p-3 border rounded bg-light">
            <div class="col-md-3">
                <label for="month" class="form-label">Pilih Bulan:</label>
                <select name="month" id="month" class="form-select">
                    <?php foreach ($months as $num => $name) : ?>
                        <option value="<?= $num ?>" <?= ($num == $month) ? 'selected' : '' ?>><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="year" class="form-label">Pilih Tahun:</label>
                <input type="number" name="year" id="year" class="form-control" value="<?= htmlspecialchars($year) ?>" min="2000" max="2099">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Tampilkan Data</button>
            </div>
        </form>

        <h1 class="mb-4">Hasil Query Database</h1>
        <?php

        // 1. Konfigurasi Database
        $servername = "localhost"; // Ganti dengan host database Anda
        $username = "root";        // Ganti dengan username database Anda
        $password = "sqlpnlbk!@#"; // Ganti dengan password database Anda
        $dbname = "db_SIPP311";   // Ganti dengan nama database Anda

        // 2. Membuat koneksi ke database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Memeriksa koneksi
        if ($conn->connect_error) {
            echo '<div class="alert alert-danger">Koneksi gagal: ' . htmlspecialchars($conn->connect_error) . '</div>';
            die();
        }

        // Menampilkan periode data yang sedang ditampilkan
        echo '<div class="alert alert-info">Menampilkan data untuk: <strong>' . htmlspecialchars($months[$month]) . ' ' . htmlspecialchars($year) . '</strong></div>';

        // 3. Menyiapkan query SELECT
        $sql = "SELECT majelis_hakim_id, majelis_hakim_text AS majelis_hakim FROM perkara_penetapan WHERE YEAR(penetapan_majelis_hakim)='$year' AND MONTH(penetapan_majelis_hakim)='$month'";

        // 4. Menjalankan query
        $result = $conn->query($sql);

        $majelisHakim = [];

        // 5. Memproses hasil query
        if ($result && $result->num_rows > 0) {
            // Output data dari setiap baris

            // Langkah Tambahan: Siapkan query untuk menghitung total perkara untuk semua hakim yang relevan
            $all_judge_ids = [];
            $temp_result = $conn->query($sql); // Jalankan query awal lagi untuk mengumpulkan ID
            while ($temp_row = $temp_result->fetch_assoc()) {
                $all_judge_ids = array_merge($all_judge_ids, explode(',', $temp_row['majelis_hakim_id']));
            }
            $unique_judge_ids = array_unique(array_filter(array_map('trim', $all_judge_ids)));

            $judge_counts = [];
            if (!empty($unique_judge_ids)) {
                $query_counts = "SELECT hakim_id, COUNT(*) as total 
                                 FROM perkara_hakim_pn 
                                 WHERE YEAR(tanggal_penetapan)='$year' AND MONTH(tanggal_penetapan)='$month' AND hakim_id IN (" . implode(',', $unique_judge_ids) . ") 
                                 GROUP BY hakim_id";
                $result_counts = $conn->query($query_counts);
                while ($count_row = $result_counts->fetch_assoc()) {
                    $judge_counts[$count_row['hakim_id']] = $count_row['total'];
                }
            }

            // Langkah Tambahan: Siapkan query untuk menghitung total perkara PUTUS untuk semua hakim yang relevan
            $judge_putus_counts = [];
            if (!empty($unique_judge_ids)) {
                $query_putus = "SELECT a.hakim_id, COUNT(b.perkara_id) AS total_perkara
                                FROM perkara_hakim_pn a
                                LEFT JOIN perkara b ON a.perkara_id = b.perkara_id
                                WHERE b.tahapan_terakhir_text = 'Putusan'
                                  AND b.proses_terakhir_text = 'Minutasi'
                                  AND YEAR(a.tanggal_penetapan)='$year' AND MONTH(a.tanggal_penetapan)='$month'
                                  AND a.hakim_id IN (" . implode(',', $unique_judge_ids) . ")
                                GROUP BY a.hakim_id";
                $result_putus = $conn->query($query_putus);
                while ($putus_row = $result_putus->fetch_assoc()) {
                    $judge_putus_counts[$putus_row['hakim_id']] = $putus_row['total_perkara'];
                }
            }

            // Langkah Tambahan: Siapkan query untuk menghitung total perkara BANDING untuk semua hakim yang relevan
            $judge_banding_counts = [];
            if (!empty($unique_judge_ids)) {
                $query_banding = "SELECT a.hakim_id, COUNT(b.perkara_id) AS total_perkara
                                FROM perkara_hakim_pn a
                                LEFT JOIN perkara b ON a.perkara_id = b.perkara_id
                                WHERE b.tahapan_terakhir_text = 'Banding'
                                  AND YEAR(a.tanggal_penetapan)='$year' AND MONTH(a.tanggal_penetapan)='$month'
                                  AND a.hakim_id IN (" . implode(',', $unique_judge_ids) . ")
                                GROUP BY a.hakim_id";
                $result_banding = $conn->query($query_banding);
                while ($banding_row = $result_banding->fetch_assoc()) {
                    $judge_banding_counts[$banding_row['hakim_id']] = $banding_row['total_perkara'];
                }
            }

            // Proses data dan masukkan ke dalam array $majelisHakim
            while ($row = $result->fetch_assoc()) {
                $ids = array_filter(array_map('trim', explode(',', $row['majelis_hakim_id'])));
                $totalPerkaraMajelis = 0;
                $totalPerkaraPutusMajelis = 0;
                $totalPerkaraBandingMajelis = 0;
                $anggotaMajelis = [];

                foreach ($ids as $id) {
                    $totalPerkara = isset($judge_counts[$id]) ? $judge_counts[$id] : 0;
                    $totalPerkaraPutus = isset($judge_putus_counts[$id]) ? $judge_putus_counts[$id] : 0;
                    $totalPerkaraBanding = isset($judge_banding_counts[$id]) ? $judge_banding_counts[$id] : 0;

                    $rasioPenyelesaian = ($totalPerkara > 0) ? ($totalPerkaraPutus / $totalPerkara) * 100 : 0;
                    $kualitasPutusan = ($totalPerkaraPutus > 0) ? max(0, (1 - ($totalPerkaraBanding / $totalPerkaraPutus))) * 100 : 100;

                    $totalPerkaraMajelis += $totalPerkara;
                    $totalPerkaraPutusMajelis += $totalPerkaraPutus;
                    $totalPerkaraBandingMajelis += $totalPerkaraBanding;

                    $anggotaMajelis[] = [
                        'id' => $id,
                        'total' => $totalPerkara,
                        'putus' => $totalPerkaraPutus,
                        'banding' => $totalPerkaraBanding,
                        'rasio_penyelesaian' => $rasioPenyelesaian,
                        'kualitas_putusan' => $kualitasPutusan
                    ];
                }

                $rasioPenyelesaianMajelis = ($totalPerkaraMajelis > 0) ? ($totalPerkaraPutusMajelis / $totalPerkaraMajelis) * 100 : 0;
                $kualitasPutusanMajelis = ($totalPerkaraPutusMajelis > 0) ? max(0, (1 - ($totalPerkaraBandingMajelis / $totalPerkaraPutusMajelis))) * 100 : 100;
                $totalRasioKeseluruhan = $rasioPenyelesaianMajelis + $kualitasPutusanMajelis;
                $persentaseTotalRasio = $totalRasioKeseluruhan / 2;


                $majelisHakim[] = [
                    'nama_majelis' => $row["majelis_hakim"],
                    'anggota' => $anggotaMajelis,
                    'total_perkara' => $totalPerkaraMajelis,
                    'total_putus' => $totalPerkaraPutusMajelis,
                    'total_banding' => $totalPerkaraBandingMajelis,
                    'rasio_penyelesaian' => $rasioPenyelesaianMajelis,
                    'kualitas_putusan' => $kualitasPutusanMajelis,
                    'total_rasio' => $totalRasioKeseluruhan,
                    'persentase_total_rasio' => $persentaseTotalRasio
                ];
            }

            // Tampilkan data dari array $majelisHakim ke dalam tabel
            echo '<div class="table-responsive">';
            echo '<table class="table table-bordered table-striped table-hover">';
            echo '<thead class="table-dark text-center">';
            echo '<tr>';
            echo '<th>No</th>';
            echo '<th>Majelis Hakim</th>';
            echo '<th>Anggota (Total | Putus | Banding | Rasio Penyelesaian | Kualitas Putusan)</th>';
            echo '<th>Total Perkara Majelis</th>';
            echo '<th>Rasio Penyelesaian Majelis</th>';
            echo '<th>Kualitas Putusan Majelis</th>';
            echo '<th>Total Nilai</th>';
            echo '<th>Persentase Total Rasio</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            $nomor = 1;
            foreach ($majelisHakim as $majelis) {
                echo '<tr>';
                echo '<td>' . $nomor++ . '</td>';
                echo '<td>' . $majelis['nama_majelis'] . '</td>';
                echo '<td>';
                echo '<ul class="list-unstyled mb-0">';
                foreach ($majelis['anggota'] as $anggota) {
                    echo '<li>' . $anggota['id'] . ' (' . $anggota['total'] . ' | ' . $anggota['putus'] . ' | ' . $anggota['banding'] . ' | <strong>' . number_format($anggota['rasio_penyelesaian'], 2) . '%</strong> | <strong>' . number_format($anggota['kualitas_putusan'], 2) . '%</strong>)</li>';
                }
                echo '</ul>';
                echo '</td>';
                echo '<td>' . $majelis['total_perkara'] . '</td>';
                echo '<td>' . number_format($majelis['rasio_penyelesaian'], 2) . '%</td>';
                echo '<td>' . number_format($majelis['kualitas_putusan'], 2) . '%</td>';
                echo '<td><strong>' . number_format($majelis['total_rasio'], 2) . '</strong></td>';
                echo '<td><strong>' . number_format($majelis['persentase_total_rasio'], 2) . '%</strong></td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        } else {
            echo '<div class="alert alert-warning">Tidak ada data yang ditemukan.</div>';
        }

        // 6. Menutup koneksi
        $conn->close();
        ?>
    </div>
</body>

</html>