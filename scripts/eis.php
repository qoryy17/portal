<?php

/**
 * Menyediakan data default jika terjadi kegagalan atau data tidak ditemukan.
 * @param string $message Pesan informasi atau error.
 * @return array Data default.
 */
function getDefaultEisData($message)
{
    return [
        'status' => 0,
        'rank' => '-',
        'category' => '-',
        'class' => '-',
        'performance' => '-',
        'compliance' => '-',
        'completeness' => '-',
        'conformity' => '-',
        'total' => 0,
        'info' => $message
    ];
}

/**
 * Mengambil data peringkat EIS dari API Badilum berdasarkan kelas dan kategori.
 *
 * @param string|int $classId ID kelas pengadilan.
 * @param string|int $categoryId ID kategori.
 * @return stdClass|null Objek hasil JSON dari API, atau null jika gagal.
 */
function fetchEisDataByCategory($classId, $categoryId)
{
    // Ambil IP publik untuk menghasilkan token yang diperlukan oleh API.
    $publicIp = @file_get_contents('https://api.ipify.org');
    if ($publicIp === false) {
        // Gagal mendapatkan IP, tidak bisa melanjutkan.
        error_log("Gagal mengambil IP publik dari api.ipify.org");
        return null;
    }

    $token1 = base64_encode($publicIp);
    // Token 2 tampaknya adalah User-Agent yang di-encode base64, ini mungkin diperlukan oleh server.
    $token2 = 'TW96aWxsYS81LjAgKE1hY2ludG9zaDsgSW50ZWwgTWFjIE9TIFggMTAuMTU7IHJ2OjEyNi4wKSBHZWNrby8yMDEwMDEwMSBGaXJlZm94LzEyNi4w';
    $currentYear = date('Y');
    $currentMonth = intval(date('m'));

    $postData = [
        'pengadilan_tinggi' => -1,
        'kategori' => $categoryId,
        'tahun_awal' => $currentYear,
        'tahun_akhir' => $currentYear,
        'bulan_awal' => $currentMonth,
        'bulan_akhir' => $currentMonth,
        'kelas' => $classId,
        'token_1' => $token1,
        'token_2' => $token2,
    ];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://evaluasi.badilum.mahkamahagung.go.id/evaluasipn',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30, // Set timeout yang wajar (30 detik)
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_HTTPHEADER => [
            'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:126.0) Gecko/20100101 Firefox/126.0',
            // Cookie ini mungkin perlu diperbarui secara berkala jika session-nya kedaluwarsa.
            'Cookie: sipapu_session=bf2db085aca428c2f38380b89dc1dc15a26f6dd0'
        ],
    ]);

    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);

    if ($error) {
        error_log("cURL Error: " . $error);
        return null;
    }

    // API tampaknya mengembalikan JSON yang dibungkus tag HTML, jadi strip_tags diperlukan.
    return json_decode(strip_tags($response));
}

/**
 * Mengambil dan memproses data capaian EIS untuk pengadilan tertentu.
 * @return array Data capaian EIS.
 */
function getCourtEisData()
{
    $courtConfig = [
        'name' => 'Pengadilan Negeri Lubuk Pakam',
        'classId' => '2',
        'categoryId' => '4'
    ];

    $apiResponse = fetchEisDataByCategory($courtConfig['classId'], $courtConfig['categoryId']);

    // Cek jika API response tidak valid atau tidak ada data
    if (!$apiResponse || !isset($apiResponse->data) || empty($apiResponse->data)) {
        return getDefaultEisData("Tidak dapat membuka EIS Badilum atau data tidak tersedia.");
    }

    $allRankings = $apiResponse->data;
    $courtName = $courtConfig['name'];

    // Cari index dari pengadilan yang kita inginkan menggunakan fungsi bawaan PHP
    $courtNamesColumn = array_column($allRankings, 3);
    $courtIndex = array_search($courtName, $courtNamesColumn);

    if ($courtIndex === false) {
        return getDefaultEisData("Data untuk '{$courtName}' tidak ditemukan dalam peringkat.");
    }

    $courtData = $allRankings[$courtIndex];

    // Data dari API tampaknya memiliki index numerik.
    // 0: Peringkat (tapi kita hitung ulang dari index), 1: Kategori, 2: Kelas, 3: Nama Satker,
    // 4: Kinerja, 5: Kepatuhan, 6: Kelengkapan, 7: Kesesuaian, 8: Total
    return [
        'status' => 1,
        'rank' => $courtIndex + 1, // Peringkat dihitung dari index array (0-based) + 1
        'category' => $courtData[1],
        'class' => $courtData[2],
        'performance' => $courtData[4],
        'compliance' => $courtData[5],
        'completeness' => $courtData[6],
        'conformity' => $courtData[7],
        'total' => $courtData[8],
        'info' => "Data berhasil diambil pada " . date('d-m-Y H:i:s')
    ];
}
