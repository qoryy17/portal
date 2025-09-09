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
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <!-- Widget Background Particles JS -->
    <canvas id="particles-js"></canvas>

    <div class="page-wrapper">

        <?php
        // Ambil data EIS dari skrip
        require_once 'scripts/eis.php';
        $eisData = getCourtEisData();

        // Ambil RSS dari backend
        $pengumuman_items = [];
        $rss_url = 'https://rss.pt-yogyakarta.go.id/?badilumpengumuman';
        // Use error handling for file loading
        $rss_content = @file_get_contents($rss_url);
        if ($rss_content !== false) {
            $rss = simplexml_load_string($rss_content);
            if ($rss && isset($rss->channel->item)) {
                foreach ($rss->channel->item as $item) {
                    $pengumuman_items[] = [
                        'title' => htmlspecialchars((string) $item->title, ENT_QUOTES, 'UTF-8'),
                        'link' => htmlspecialchars((string) $item->link, ENT_QUOTES, 'UTF-8'),
                        'pubDate' => isset($item->pubDate) ? date('d/m/Y', strtotime((string) $item->pubDate)) : '',
                    ];
                }
            }
        }

        // Data Aplikasi
        $applications = [
            ['name' => 'Website', 'url' => 'https://pn-lubukpakam.go.id/', 'icon' => 'fa-globe'],
            ['name' => 'SIPP 6.0.0', 'url' => 'http://192.168.1.147/SIPP330', 'icon' => 'fa-scale-balanced'],
            ['name' => 'MIS 6.0.0', 'url' => 'http://192.168.1.147/mis', 'icon' => 'fa-chart-pie'],
            ['name' => 'PTSP+ Lama', 'url' => 'http://192.168.1.147/ptsp', 'icon' => 'fa-desktop'],
            ['name' => 'PTSP+ Baru', 'url' => 'http://192.168.1.147/e-ptsp', 'icon' => 'fa-display'],
            ['name' => 'E-Court', 'url' => 'https://ecourt.mahkamahagung.go.id/Login', 'icon' => 'fa-gavel'],
            ['name' => 'E-Berpadu', 'url' => 'https://eberpadu.mahkamahagung.go.id/', 'icon' => 'fa-users'],
            ['name' => 'E-SuKa', 'url' => 'https://www.e-suka.pn-lubukpakam.go.id/', 'icon' => 'fa-file-lines'],
            ['name' => 'EIS Badilum', 'url' => 'https://evaluasi.badilum.mahkamahagung.go.id/', 'icon' => 'fa-chart-line'],
            ['name' => 'SAKTI KemenKeu', 'url' => 'https://sakti.kemenkeu.go.id/', 'icon' => 'fa-money-bill'],
            ['name' => 'SIWAKJON 3', 'url' => 'http://siwakjon3.local', 'icon' => 'fa-landmark'],
            ['name' => 'SiSuper', 'url' => 'http://esurvey.badilum.mahkamahagung.go.id/', 'icon' => 'fa-star'],
            ['name' => 'Eraterang', 'url' => 'https://eraterang.badilum.mahkamahagung.go.id/masuk', 'icon' => 'fa-file-signature'],
            ['name' => 'Direktori Putusan', 'url' => 'https://putusan3.mahkamahagung.go.id/', 'icon' => 'fa-book-bookmark'],
            ['name' => 'Perkusi', 'url' => 'https://eksekusi.badilum.mahkamahagung.go.id/', 'icon' => 'fa-hammer'],
            ['name' => 'SIWAS', 'url' => 'https://siwas.mahkamahagung.go.id/', 'icon' => 'fa-bullhorn'],
        ];
        ?>

        <!-- Widget Slider Pengumuman -->
        <div id="pengumuman-container">
            <div class="container-fluid d-flex align-items-center">
                <span class="badge bg-danger me-3 flex-shrink-0">PENGUMUMAN</span>
                <div class="ticker-wrap">
                    <div id="pengumuman-slider">
                        <?php if (!empty($pengumuman_items)) : ?>
                            <?php foreach ($pengumuman_items as $p) : ?>
                                <a href="<?= $p['link'] ?>" target="_blank" class="text-white text-decoration-none">
                                    <span class="badge bg-warning text-dark me-2"><?= $p['pubDate'] ?></span> <?= $p['title'] ?>
                                </a>
                                <span class="mx-3 text-white-50">|</span>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <span class="text-white">Tidak ada pengumuman.</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Konten -->
        <main class="portal-container">
            <div class="container text-center">
                <!-- Widget Tanggal dan Waktu -->
                <div id="jam-hari" class="mb-2">Loading Waktu...</div>

                <h1 id="judul" class="display-4 fw-bold">Portal Aplikasi SPBE</h1>
                <p id="subjudul" class="lead mb-5">
                    Pengadilan Negeri Lubuk Pakam
                </p>

                <!-- Bagian Statistik EIS -->
                <?php if ($eisData['status'] == 1) : ?>
                    <!-- Peringkat & Capaian -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="card portal-card h-100 text-start">
                                <div class="card-body p-4">
                                    <h5 class="card-title text-muted mb-2">Peringkat Nasional</h5>
                                    <div class="d-flex align-items-baseline">
                                        <h1 class="display-3 fw-bold me-2 text-warning"><?= $eisData['rank']; ?></h1>
                                        <p class="mb-0">pada Kelas <?= $eisData['class']; ?> / Kategori <?= $eisData['category']; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card portal-card h-100 text-start">
                                <div class="card-body p-4">
                                    <h5 class="card-title text-muted mb-2">Total Poin Capaian</h5>
                                    <div class="d-flex align-items-baseline">
                                        <h1 class="display-3 fw-bold text-warning"><?= $eisData['total']; ?></h1>
                                    </div>
                                    <p class="mb-0">Total poin dari 4 indikator EIS.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Indikator -->
                    <div class="row g-4 pb-5">
                        <div class="col-xl-3 col-md-6">
                            <div class="card portal-card h-100">
                                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                                    <div class="text-start">
                                        <h2 class="fw-bold mb-1"><?= $eisData['performance']; ?></h2>
                                        <span class="text-muted">Kinerja</span>
                                    </div>
                                    <i class="fas fa-chart-line fa-3x text-warning opacity-25"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card portal-card h-100">
                                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                                    <div class="text-start">
                                        <h2 class="fw-bold mb-1"><?= $eisData['compliance']; ?></h2>
                                        <span class="text-muted">Kepatuhan</span>
                                    </div>
                                    <i class="fas fa-user-check fa-3x text-warning opacity-25"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card portal-card h-100">
                                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                                    <div class="text-start">
                                        <h2 class="fw-bold mb-1"><?= $eisData['completeness']; ?></h2>
                                        <span class="text-muted">Kelengkapan</span>
                                    </div>
                                    <i class="fas fa-folder-open fa-3x text-warning opacity-25"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card portal-card h-100">
                                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                                    <div class="text-start">
                                        <h2 class="fw-bold mb-1"><?= $eisData['conformity']; ?></h2>
                                        <span class="text-muted">Kesesuaian</span>
                                    </div>
                                    <i class="fas fa-check-double fa-3x text-warning opacity-25"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Gagal Memuat Data EIS:</strong> <?= $eisData['info']; ?>
                    </div>
                <?php endif; ?>

                <!-- Widget Button Group Link -->
                <div class="row g-4 justify-content-center">
                    <?php foreach ($applications as $app) : ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <a target="_blank" href="<?= $app['url']; ?>" class="card portal-card h-100 text-decoration-none">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                                    <i class="fas <?= $app['icon']; ?> fa-3x mb-3 text-warning"></i>
                                    <h5 class="card-title mb-0"><?= $app['name']; ?></h5>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>

        <footer class="py-3">
            <div class="container text-center">
                Made with <i class="fas fa-heart text-danger"></i> by Qori Chairawan, S.Kom
            </div>
        </footer>
    </div><!-- .page-wrapper -->

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="assets/js/portal.js"></script>
</body>

</html>