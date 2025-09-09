<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="Qori Chairawan, S.Kom">
    <meta name="keyword" content="Portal, Layanan, Aplikasi, Pengadilan Negeri Lubuk Pakam">
    <meta name="description" content="Portal Layanan Aplikasi">
    <title>Portal Sistem Pemerintahan Berbasis Elektronik (SPBE)</title>
    <!-- Favicon Logo -->
    <link rel="shortcut icon" href="logo.png" type="image/png">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <!-- Widget Background Particles JS -->
    <div id="particles-js"></div>
    <?php
    // Ambil RSS dari backend
    $pengumuman = [];
    $rss_url = 'https://rss.pt-yogyakarta.go.id/?badilumpengumuman';
    $rss = @simplexml_load_file($rss_url);

    if ($rss && isset($rss->channel->item)) {
        foreach ($rss->channel->item as $item) {
            $title = htmlspecialchars((string) $item->title, ENT_QUOTES, 'UTF-8');
            $link = htmlspecialchars((string) $item->link, ENT_QUOTES, 'UTF-8');
            $pubDate = isset($item->pubDate) ? date('d/m/Y', strtotime((string) $item->pubDate)) : '';
            $pengumuman[] = "<a href=\"$link\" target=\"_blank\" style=\"color:#222;text-decoration:underline;\">[$pubDate] $title</a>";
        }
    }
    ?>
    <!-- Widget Slider Pengumuman -->
    <div id="pengumuman-slider">
        <span class="marquee">
            Pengumuman Direktorat Jenderal Badan Peradilan Umum :
            <?php
            if (!empty($pengumuman)) {
                echo implode(' &nbsp;|&nbsp; ', $pengumuman);
            } else {
                echo "Tidak ada pengumuman.";
            }
            ?>
        </span>
    </div>
    <!-- Konten -->
    <div class="container portal-container">
        <!-- Widget Tanggal dan Waktu -->
        <div id="jam-hari">Loading Waktu...</div>

        <h1 id="judul" class="blink">Sistem Pemerintahan Berbasis Elektronik (SPBE)</h1>
        <p id="subjudul">
            Silakan pilih salah satu layanan aplikasi di bawah untuk mengakses fitur yang Anda butuhkan.
        </p>
        <!-- Widget Button Group Link -->
        <div class="d-flex justify-content-center flex-wrap" id="tombol-container">
            <a target="_blank" href="http://192.168.1.147/SIPP330" class="btn btn-portal">SIPP 6.0.0</a>
            <a target="_blank" href="http://192.168.1.147/mis" class="btn btn-portal">MIS 6.0.0</a>
            <a target="_blank" href="http://192.168.1.147/ptsp" class="btn btn-portal">PTSP+ Lama</a>
            <a target="_blank" href="http://192.168.1.147/e-ptsp" class="btn btn-portal">PTSP+ Baru</a>
            <a target="_blank" href="http://siwakjon3.local" class="btn btn-portal">SIWAKJON 3</a>
            <a target="_blank" href="http://esurvey.badilum.mahkamahagung.go.id/" class="btn btn-portal">SiSuper</a>
            <a target="_blank" href="https://evaluasi.badilum.mahkamahagung.go.id/evaluasi3" class="btn btn-portal">
                EIS Badilum
            </a>
            <a target="_blank" href="https://eraterang.badilum.mahkamahagung.go.id/masuk" class="btn btn-portal">
                Eraterang
            </a>
            <a target="_blank" href="https://ecourt.mahkamahagung.go.id/Login" class="btn btn-portal">E-Court</a>
            <a target="_blank" href="https://eberpadu.mahkamahagung.go.id/" class="btn btn-portal">E-Berpadu</a>
            <a target="_blank" href="https://www.e-suka.pn-lubukpakam.go.id/" class="btn btn-portal">E-SuKa</a>
            <a target="_blank" href="https://putusan3.mahkamahagung.go.id/" class="btn btn-portal">Direktori Putusan</a>
            <a target="_blank" href="https://eksekusi.badilum.mahkamahagung.go.id/" class="btn btn-portal">Perkusi</a>
            <a target="_blank" href="https://siwas.mahkamahagung.go.id/" class="btn btn-portal">SIWAS</a>
        </div>
    </div>

    <footer>
        Made By Qori Chairawan, S.Kom
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/particles.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <!-- Custom Js -->
    <script src="assets/js/main.js"></script>

</body>

</html>