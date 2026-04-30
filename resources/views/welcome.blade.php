<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Geo-Road — SIG Manajemen Kerusakan Jalan</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{
            --gr-gold:#facc15;
            --gr-gold-2:#eab308;
            --gr-navy:#0f172a;
            --gr-overlay:rgba(2,6,23,.75);
            --gr-bg:#f8fafc;
            --gr-white:#ffffff;
            --gr-text:#111827;
            --gr-sub:#6b7280;
            --gr-border:rgba(148,163,184,.22);
            --gr-shadow:0 22px 80px rgba(2,6,23,.10);
        }
        body.grlp-body{
            background: var(--gr-bg);
            color: var(--gr-text);
        }
        .grlp-section{padding:96px 0}
        .grlp-title{
            font-weight: 800;
            letter-spacing: .2px;
        }
        .grlp-subtitle{color: var(--gr-sub)}

        .grlp-navbar{
            background: rgba(255,255,255,.72);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(148,163,184,.18);
        }
        .grlp-navbar .navbar-brand{font-weight: 800; letter-spacing: .3px}
        .grlp-brandmark{
            width: 42px;
            height: 42px;
            border-radius: 16px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            background: linear-gradient(135deg, var(--gr-navy) 0%, rgba(2,6,23,.92) 100%);
            box-shadow: 0 16px 40px rgba(2,6,23,.18);
        }
        .grlp-brandmark i{color: var(--gr-gold); font-size: 18px}
        .grlp-navlink{
            color: rgba(15,23,42,.88) !important;
            font-weight: 600;
            position: relative;
            padding-left: .85rem;
            padding-right: .85rem;
            transition: color .18s ease;
        }
        .grlp-navlink:hover{color: rgba(234,179,8,1) !important}
        .grlp-btn-primary{
            background: linear-gradient(135deg, var(--gr-gold) 0%, var(--gr-gold-2) 100%);
            color: #111827;
            border: 0;
            font-weight: 800;
            box-shadow: 0 16px 40px rgba(250,204,21,.22);
        }
        .grlp-btn-primary:hover{filter: brightness(.98)}
        .grlp-btn-outline{
            border: 1px solid rgba(255,255,255,.40);
            color: rgba(255,255,255,.92);
            font-weight: 700;
        }
        .grlp-btn-outline:hover{
            background: rgba(255,255,255,.10);
            border-color: rgba(255,255,255,.50);
            color: rgba(255,255,255,.96);
        }

        .grlp-hero{
            position: relative;
            min-height: 100vh;
            background-position: center;
            background-size: cover;
            overflow: visible;
        }
        .grlp-hero::before{
            content:"";
            position:absolute;
            inset:0;
            background:
                radial-gradient(900px 560px at 18% 10%, rgba(250,204,21,.22), transparent 58%),
                radial-gradient(900px 560px at 90% 18%, rgba(14,165,233,.16), transparent 60%),
                linear-gradient(180deg, rgba(2,6,23,.62) 0%, rgba(2,6,23,.78) 55%, rgba(2,6,23,.90) 100%);
        }
        .grlp-hero > *{position:relative}
        .grlp-hero h1{
            font-weight: 900;
            letter-spacing: .3px;
        }
        .grlp-hero h2{max-width: 700px}
        .grlp-hero p{max-width: 700px}

        .grlp-instansi{
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 0;
            background: transparent;
            border: 0;
            border-radius: 0;
            backdrop-filter: none;
        }
        .grlp-logo-card{
            width: 76px;
            height: 76px;
            border-radius: 20px;
            display: grid;
            place-items: center;
            background: rgba(255,255,255,.88);
            border: 1px solid rgba(148,163,184,.20);
            box-shadow: 0 18px 55px rgba(2,6,23,.22);
        }
        .grlp-logo-card img{width: 60px; height: 60px; object-fit: contain}
        .grlp-instansi .k1{
            color: rgba(255,255,255,.72);
            font-weight: 700;
            letter-spacing: .14px;
            text-transform: uppercase;
            font-size: 13px;
        }
        .grlp-instansi .k2{
            color: rgba(255,255,255,.94);
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .22px;
            line-height: 1.25;
            font-size: 18px;
        }
        .grlp-instansi-h1{
            margin: 0;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .22px;
            line-height: 1.15;
            font-size: 1.85rem;
        }
        .grlp-instansi-h1 .k1{
            display:block;
            color: rgba(255,255,255,.72);
            font-weight: 700;
            letter-spacing: .14px;
            font-size: .82rem;
            margin-bottom: .25rem;
        }
        .grlp-instansi-h1 .k2{
            display:block;
            color: rgba(255,255,255,.94);
        }

        .grlp-floating{
            position: absolute;
            left: 0;
            right: 0;
            bottom: -56px;
        }
        .grlp-float-card{
            border-radius: 22px;
            background: rgba(255,255,255,.92);
            border: 1px solid rgba(148,163,184,.18);
            box-shadow: var(--gr-shadow);
            padding: 18px 18px;
            height: 100%;
        }
        .grlp-float-icon{
            width: 44px;
            height: 44px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--gr-gold) 0%, var(--gr-gold-2) 100%);
            color: rgba(15,23,42,.92);
            box-shadow: 0 18px 45px rgba(250,204,21,.22);
        }
        .grlp-float-title{font-weight: 800}
        .grlp-float-text{color: var(--gr-sub)}

        .grlp-section-white{background: #ffffff}
        .grlp-section-padtop{padding-top: 160px}

        .grlp-card{
            border-radius: 22px;
            border: 1px solid rgba(148,163,184,.16);
            background: rgba(255,255,255,.96);
            box-shadow: var(--gr-shadow);
        }
        .grlp-card-line{
            width: 38px;
            height: 4px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--gr-gold) 0%, var(--gr-gold-2) 100%);
        }
        .grlp-hover{
            transition: transform .22s ease, box-shadow .22s ease;
        }
        .grlp-hover:hover{
            transform: translateY(-4px);
            box-shadow: 0 28px 90px rgba(2,6,23,.14);
        }
        .grlp-stat-icon{
            width: 54px;
            height: 54px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(250,204,21,1) 0%, rgba(234,179,8,1) 100%);
            box-shadow: 0 18px 45px rgba(250,204,21,.22);
            color: rgba(15,23,42,.92);
        }
        .grlp-stat-label{color: var(--gr-sub); font-weight: 600}
        .grlp-stat-value{font-weight: 900; letter-spacing: .2px}

        .grlp-statcard{
            border-radius: 22px;
            border: 1px solid rgba(148,163,184,.14);
            box-shadow: var(--gr-shadow);
            color: rgba(255,255,255,.96);
            position: relative;
            overflow: hidden;
        }
        .grlp-statcard::before{
            content:"";
            position:absolute;
            inset:0;
            background: radial-gradient(520px 220px at 18% 0%, rgba(255,255,255,.18), transparent 60%);
            pointer-events:none;
        }
        .grlp-statcard > *{position:relative}
        .grlp-statcard .grlp-stat-label{color: rgba(255,255,255,.80)}
        .grlp-statcard .grlp-stat-value{color: rgba(255,255,255,.98)}
        .grlp-statcard .grlp-stat-icon{
            width: 52px;
            height: 52px;
            background: rgba(255,255,255,.16);
            border: 1px solid rgba(255,255,255,.20);
            box-shadow: 0 18px 55px rgba(2,6,23,.18);
            color: rgba(255,255,255,.96);
        }
        .grlp-statcard--baik{background: linear-gradient(135deg,#22c55e 0%,#16a34a 100%)}
        .grlp-statcard--sedang{background: linear-gradient(135deg,#2563eb 0%,#1d4ed8 100%)}
        .grlp-statcard--berat{background: linear-gradient(135deg,#ef4444 0%,#b91c1c 100%)}
        .grlp-statcard--ringan{
            background: linear-gradient(135deg,#facc15 0%,#eab308 100%);
            color: rgba(15,23,42,.96);
        }
        .grlp-statcard--ringan .grlp-stat-label{color: rgba(15,23,42,.72)}
        .grlp-statcard--ringan .grlp-stat-value{color: rgba(15,23,42,.95)}
        .grlp-statcard--ringan .grlp-stat-icon{
            background: rgba(15,23,42,.10);
            border: 1px solid rgba(15,23,42,.14);
            color: rgba(15,23,42,.92);
        }

        .grlp-map-mini{
            height: 340px;
            border-radius: 22px;
            overflow: hidden;
            border: 1px solid rgba(148,163,184,.16);
        }
        .grlp-bullet{
            display:flex;
            gap:10px;
            align-items:flex-start;
            margin-bottom: 10px;
        }
        .grlp-bullet i{color: var(--gr-gold-2); margin-top: 2px}
        .grlp-footer{
            background: linear-gradient(180deg, rgba(15,23,42,1) 0%, rgba(2,6,23,1) 100%);
            border-top: 1px solid rgba(148,163,184,.14);
        }
        .grlp-footer .muted{color: rgba(226,232,240,.72)}
        .grlp-social a{
            width: 40px;
            height: 40px;
            border-radius: 999px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            color: rgba(248,250,252,.92);
            border: 1px solid rgba(148,163,184,.22);
            background: rgba(255,255,255,.06);
            transition: transform .18s ease, background-color .18s ease, border-color .18s ease;
        }
        .grlp-social a:hover{
            transform: translateY(-2px);
            background: rgba(250,204,21,.14);
            border-color: rgba(250,204,21,.28);
            color: rgba(250,204,21,1);
        }

        @media (max-width: 991.98px){
            .grlp-floating{position: static; bottom: auto; margin-top: 26px}
            .grlp-section-padtop{padding-top: 96px}
            .grlp-hero h1{font-size: 3rem}
        }
        @media (max-width: 575.98px){
            .grlp-instansi{flex-wrap: wrap}
            .grlp-hero h1{font-size: 2.6rem}
            .grlp-instansi .k2{font-size: 16px}
            .grlp-instansi-h1{font-size: 1.35rem}
            .grlp-instansi-h1 .k1{font-size: .78rem}
        }
    </style>
</head>
<body class="grlp-body" id="home">
    @php($logo1Url = url('/img/'.rawurlencode('logo (1).jpeg')))
    @php($logo2Url = url('/img/'.rawurlencode('logo (2).jpeg')))
    <div id="gr-loader" class="gr-loader">
        <div class="text-center text-white">
            <div class="gr-spinner mx-auto mb-3"></div>
            <div class="fw-semibold">Geo-Road</div>
            <div class="small text-white-50">Memuat landing page...</div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light sticky-top grlp-navbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#home">
                <span class="grlp-brandmark"><i class="bi bi-map-fill"></i></span>
                <span>Geo-Road</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item"><a class="nav-link grlp-navlink" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link grlp-navlink" href="#tentang">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link grlp-navlink" href="#statistik">Statistik</a></li>
                    <li class="nav-item"><a class="nav-link grlp-navlink" href="{{ route('webgis.public') }}">WebGIS</a></li>
                    <li class="nav-item ms-lg-2">
                        @auth
                            <a class="btn btn-sm grlp-btn-primary rounded-4 px-3 py-2" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i> Dashboard
                            </a>
                        @else
                            <a class="btn btn-sm grlp-btn-primary rounded-4 px-3 py-2" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login
                            </a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="grlp-hero d-flex align-items-center" style="background-image:url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=2400&q=80');">
        <div class="container position-relative">
            <div class="row align-items-center min-vh-100 py-5">
                <div class="col-lg-8 text-white">
                    <div class="grlp-instansi mb-4" data-aos="fade-up">
                        <div class="d-flex align-items-center gap-2">
                            <div class="grlp-logo-card">
                                <img src="{{ $logo1Url }}" alt="Logo BMBK">
                            </div>
                            <div class="grlp-logo-card">
                                <img src="{{ $logo2Url }}" alt="Logo Provinsi Lampung">
                            </div>
                        </div>
                        <div class="ms-1">
                            <h1 class="grlp-instansi-h1">
                                <span class="k1">PEMERINTAH PROVINSI LAMPUNG</span>
                                <span class="k2">DINAS BINA MARGA DAN BINA KONSTRUKSI PROVINSI LAMPUNG</span>
                            </h1>
                        </div>
                    </div>

                 
                    <h2 class="h4 fw-semibold mb-3" style="color:rgba(255,255,255,.86)" data-aos="fade-up" data-aos-delay="140">
                        Sistem Informasi Geografis Manajemen Kerusakan Jalan Provinsi Lampung
                    </h2>
                    <p class="lead mb-4" style="color:rgba(255,255,255,.76)" data-aos="fade-up" data-aos-delay="200">
                        Platform monitoring kondisi jalan, pendataan kerusakan, visualisasi peta interaktif, dan dukungan pengambilan keputusan penanganan berbasis data.
                    </p>

                    <div class="d-flex flex-wrap gap-3" data-aos="fade-up" data-aos-delay="260">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn grlp-btn-primary rounded-4 px-4 py-2">
                                <i class="bi bi-compass me-2"></i> Jelajahi Sistem
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn grlp-btn-primary rounded-4 px-4 py-2">
                                <i class="bi bi-compass me-2"></i> Jelajahi Sistem
                            </a>
                        @endauth
                        <a href="{{ route('webgis.public') }}" class="btn grlp-btn-outline rounded-4 px-4 py-2">
                            <i class="bi bi-map me-2"></i> Lihat Peta
                        </a>
                    </div>
                </div>
            </div>

            <div class="grlp-floating">
                <div class="row g-3">
                    <div class="col-md-4" data-aos="fade-up">
                        <div class="grlp-float-card grlp-hover">
                            <div class="d-flex align-items-center gap-3">
                                <span class="grlp-float-icon"><i class="bi bi-activity"></i></span>
                                <div>
                                    <div class="grlp-float-title">Monitoring</div>
                                    <div class="grlp-float-text small">Ringkas dan terstruktur untuk evaluasi berkala.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="80">
                        <div class="grlp-float-card grlp-hover">
                            <div class="d-flex align-items-center gap-3">
                                <span class="grlp-float-icon"><i class="bi bi-globe2"></i></span>
                                <div>
                                    <div class="grlp-float-title">WebGIS Interaktif</div>
                                    <div class="grlp-float-text small">Layer kondisi jalan dan detail ruas dalam peta.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="160">
                        <div class="grlp-float-card grlp-hover">
                            <div class="d-flex align-items-center gap-3">
                                <span class="grlp-float-icon"><i class="bi bi-clipboard-data"></i></span>
                                <div>
                                    <div class="grlp-float-title">Keputusan Cepat</div>
                                    <div class="grlp-float-text small">Grafik & statistik untuk prioritas penanganan.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grlp-section grlp-section-white grlp-section-padtop" id="statistik">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <div class="grlp-title h2 mb-2">Ringkasan Statistik</div>
                <div class="grlp-subtitle">Gambaran cepat kondisi ruas jalan yang tercatat.</div>
            </div>

            <div class="row g-3 g-lg-4 mb-4">
                <div class="col-md-6 col-xl-3" data-aos="fade-up">
                    <div class="grlp-statcard grlp-statcard--baik grlp-hover p-4 h-100">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <div class="grlp-stat-label">Baik</div>
                                <div class="display-6 grlp-stat-value mb-0" data-counter="{{ $stats['baik'] ?? 0 }}">0</div>
                            </div>
                            <span class="grlp-stat-icon"><i class="bi bi-check2-circle"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3" data-aos="fade-up" data-aos-delay="70">
                    <div class="grlp-statcard grlp-statcard--sedang grlp-hover p-4 h-100">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <div class="grlp-stat-label">Sedang</div>
                                <div class="display-6 grlp-stat-value mb-0" data-counter="{{ $stats['rusak_sedang'] ?? 0 }}">0</div>
                            </div>
                            <span class="grlp-stat-icon"><i class="bi bi-cone-striped"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3" data-aos="fade-up" data-aos-delay="140">
                    <div class="grlp-statcard grlp-statcard--ringan grlp-hover p-4 h-100">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <div class="grlp-stat-label">Rusak Ringan</div>
                                <div class="display-6 grlp-stat-value mb-0" data-counter="{{ $stats['rusak_ringan'] ?? 0 }}">0</div>
                            </div>
                            <span class="grlp-stat-icon"><i class="bi bi-exclamation-circle"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3" data-aos="fade-up" data-aos-delay="210">
                    <div class="grlp-statcard grlp-statcard--berat grlp-hover p-4 h-100">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <div class="grlp-stat-label">Rusak Berat</div>
                                <div class="display-6 grlp-stat-value mb-0" data-counter="{{ $stats['rusak_berat'] ?? 0 }}">0</div>
                            </div>
                            <span class="grlp-stat-icon"><i class="bi bi-exclamation-triangle"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 g-lg-4">
                <div class="col-lg-6" data-aos="fade-up">
                    <div class="grlp-card p-4 p-lg-5">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <div class="grlp-card-line mb-2"></div>
                                <div class="fw-bold">Kondisi Jalan</div>
                                <div class="text-muted small">Proporsi kondisi dari total data.</div>
                            </div>
                        </div>
                        <div style="height:340px">
                            <canvas id="pieKondisi"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="80">
                    <div class="grlp-card p-4 p-lg-5">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <div class="grlp-card-line mb-2"></div>
                                <div class="fw-bold">Kerusakan per Kabupaten</div>
                                <div class="text-muted small">Top kabupaten dengan jumlah data rusak terbanyak.</div>
                            </div>
                        </div>
                        <div style="height:340px">
                            <canvas id="barKabupaten"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grlp-section" id="tentang">
        <div class="container">
            <div class="row g-4 align-items-start">
                <div class="col-lg-6" data-aos="fade-up">
                    <div class="grlp-title h2 mb-3">Tentang Geo-Road</div>
                    <p class="grlp-subtitle mb-4">
                        Geo-Road dirancang sebagai sistem SIG modern untuk membantu pemerintah dalam monitoring kondisi jalan, pendataan kerusakan, serta visualisasi spasial yang mudah dipahami. Sistem ini mendukung standardisasi data, percepatan analisis, dan pengambilan keputusan penanganan yang transparan dan berbasis bukti.
                    </p>
                    <div class="mb-4">
                        <div class="grlp-bullet">
                            <i class="bi bi-check2-circle"></i>
                            <div><span class="fw-semibold">Standardisasi data</span> untuk pemetaan kondisi jalan yang konsisten.</div>
                        </div>
                        <div class="grlp-bullet">
                            <i class="bi bi-check2-circle"></i>
                            <div><span class="fw-semibold">Visualisasi spasial</span> yang mudah dibaca untuk monitoring harian.</div>
                        </div>
                        <div class="grlp-bullet">
                            <i class="bi bi-check2-circle"></i>
                            <div><span class="fw-semibold">Dukungan keputusan</span> berbasis statistik dan sebaran kerusakan.</div>
                        </div>
                        <div class="grlp-bullet">
                            <i class="bi bi-check2-circle"></i>
                            <div><span class="fw-semibold">Transparansi penanganan</span> melalui data yang dapat ditelusuri.</div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6" data-aos="fade-up" data-aos-delay="70">
                            <div class="grlp-card grlp-hover p-4 h-100">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="grlp-stat-icon" style="width:48px;height:48px"><i class="bi bi-map"></i></span>
                                    <div>
                                        <div class="fw-bold">Peta Interaktif</div>
                                        <div class="text-muted small">Layer ruas jalan, legenda, filter kondisi, dan heatmap.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" data-aos="fade-up" data-aos-delay="140">
                            <div class="grlp-card grlp-hover p-4 h-100">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="grlp-stat-icon" style="width:48px;height:48px"><i class="bi bi-graph-up"></i></span>
                                    <div>
                                        <div class="fw-bold">Analitik Cepat</div>
                                        <div class="text-muted small">Ringkasan statistik dan grafik untuk monitoring.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="60">
                    <div class="grlp-card p-4 p-lg-5">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <div class="grlp-card-line mb-2"></div>
                                <div class="fw-bold">Preview Peta Lampung</div>
                                <div class="text-muted small">Mini map dengan marker pusat Provinsi Lampung.</div>
                            </div>
                        </div>
                        <div id="miniMap" class="grlp-map-mini"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="grlp-footer py-5">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7 text-white">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="grlp-brandmark" style="width:40px;height:40px;border-radius:16px"><i class="bi bi-map-fill" style="font-size:16px"></i></span>
                        <div class="fw-bold">Geo-Road</div>
                    </div>
                    <div class="muted">
                        Sistem Informasi Geografis Manajemen Data Kerusakan Jalan • Dinas Bina Marga dan Bina Konstruksi Provinsi Lampung
                    </div>
                </div>
                <div class="col-lg-5 d-flex flex-column flex-lg-row justify-content-lg-end align-items-lg-center gap-3">
                    <div class="grlp-social d-flex gap-2 justify-content-lg-end">
                        <a href="javascript:void(0)" aria-label="Website"><i class="bi bi-globe"></i></a>
                        <a href="javascript:void(0)" aria-label="Email"><i class="bi bi-envelope"></i></a>
                        <a href="javascript:void(0)" aria-label="Telepon"><i class="bi bi-telephone"></i></a>
                    </div>
                    <div class="muted text-lg-end">© {{ now()->year }} Pemerintah Provinsi Lampung</div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        (() => {
            if (window.Chart) {
                Chart.defaults.color = 'rgba(15,23,42,.88)';
                Chart.defaults.borderColor = 'rgba(148,163,184,.35)';
            }
            const miniMap = L.map('miniMap', { zoomControl: false, scrollWheelZoom: false }).setView([-5.45, 105.27], 8);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(miniMap);
            L.marker([-5.45, 105.27]).addTo(miniMap).bindPopup('Provinsi Lampung');

            const kondisi = @json($kondisiBreakdown ?? []);
            const labels = Object.keys(kondisi);
            const values = Object.values(kondisi);
            const colors = {
                'Baik': '#22c55e',
                'Rusak Ringan': '#facc15',
                'Rusak Sedang': '#fb923c',
                'Rusak Berat': '#ef4444',
            };

            const pieEl = document.getElementById('pieKondisi');
            if (pieEl && window.Chart) {
                new Chart(pieEl, {
                    type: 'pie',
                    data: {
                        labels,
                        datasets: [{
                            data: values,
                            backgroundColor: labels.map(l => colors[l] || '#94a3b8'),
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            }

            const barEl = document.getElementById('barKabupaten');
            const barData = @json(($kerusakanKabupaten ?? collect())->map(fn($r) => ['kabupaten' => $r->kabupaten, 'total' => (int) $r->total])->values());
            if (barEl && window.Chart) {
                new Chart(barEl, {
                    type: 'bar',
                    data: {
                        labels: barData.map(x => x.kabupaten),
                        datasets: [{
                            label: 'Jumlah Data Rusak',
                            data: barData.map(x => x.total),
                            backgroundColor: 'rgba(250,204,21,.75)',
                            borderRadius: 10,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: { y: { beginAtZero: true } },
                        plugins: { legend: { display: false } }
                    }
                });
            }
        })();
    </script>
</body>
</html>
