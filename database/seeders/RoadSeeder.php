<?php

namespace Database\Seeders;

use App\Models\Road;
use Illuminate\Database\Seeder;

class RoadSeeder extends Seeder
{
    public function run(): void
    {
        Road::query()
            ->where('nama_ruas', 'like', 'Ruas Jalan Lampung %')
            ->delete();

        $prioritasList = Road::prioritasOptions();

        $jenisKerusakanList = [
            'Retak memanjang',
            'Lubang (potholes)',
            'Alur (rutting)',
            'Gelombang (corrugation)',
            'Amblas/penurunan badan jalan',
            'Kerusakan bahu jalan',
        ];

        $raw = <<<'TEXT'
1
1
2.890
Jalan Zainal Abidin Pagaralam (Kalianda)
5°42'53.38" / 105°35'14.97"
5°44'19.13" / 105°35'29.14"
Lampung Selatan
2
2
30.250
Kalianda - Kunyir - Gayam
5°44'19.13" / 105°35'29.14"
5°45'55.19" / 105°42'5.41"
Lampung Selatan
3
3
11.297
Gayam - Ketapang
5°45'55.19" / 105°42'5.41"
5°44'16.15" / 105°46'58.74"
Lampung Selatan
4
4
25.012
Sp. Sidomulyo - Belimbing Sari
5°36'39.67" / 105°30'46.06"
5°29'59.60" / 105°39'14.90"
Lampung Selatan
5
5
9.308
Belimbing Sari - Jabung
5°29'59.60" / 105°39'14.90"
5°27'51.94" / 105°40'10.32"
Lampung Timur
6
6
22.962
Jabung - Sp. Labuhan Maringgai
5°27'51.94" / 105°40'10.32"
5°20'53.06" / 104°47'33.64"
Lampung Timur
7
7
2.770
Jalan Ahmad Yani (Metro)
5°6'51.11" / 105°18'32.55"
5°7'48.05" / 105°19'15.48"
Metro
8
8
24.251
Metro - Tanjung Kari
5°7'48.05" / 105°19'15.48"
5°9'41.77" / 105°30'29.75"
Lampung Timur
9
9
7.876
Nyampir - Tanjung Kari
5°6'0.01" / 105°31'23.74"
5°9'41.77" / 105°30'29.75"
Lampung Timur
10
10
24.491
Tanjung Kari - Pugung Raharjo
5°9'41.77" / 105°30'29.75"
5°18'48.45" / 105°33'41.44"
Lampung Timur
11
11
26.558
Pugung Raharjo - Jabung
5°18'48.45" / 105°33'41.44"
5°27'51.94" / 105°40'10.32"
Lampung Timur
12
12
3.315
Jalan Mayjen. HM. Ryacudu (Bandar Lampung)
5°22'53.89" / 105°17'7.13"
5°21'37.86" / 105°18'15.85"
Bandar Lampung
13
13
20.468
Sp. Korpri - Sukadamai
5°21'37.86" / 105°18'15.85"
5°12'32.57" / 105°19'17.03"
Lampung Selatan
14
14
4.419
Sukadamai - Kibang
5°12'32.57" / 105°19'17.03"
5°10'15.25" / 105°18'47.49"
Lampung Timur
15
15
4.568
Jalan Budi Utomo (Metro)
5°10'15.25" / 105°18'47.49"
5°8'14.43" / 105°18'7.56"
Metro
16
15
2.297
Jalan Soekarno Hatta (Metro)
5°8'14.43" / 105°18'7.56"
5°7'47.48" / 105°17'3.89"
Metro
17
16
14.551
Sp. Korpri - Purwotani
5°21'37.86" / 105°18'15.85"
5°17'42.13" / 105°24'16.94"
Lampung Selatan
18
17
1.505
Jalan Veteran (Metro)
5°6'41.40" / 105°17'12.87"
5°6'13.92" / 105°17'53.25"
Metro
19
17
3.517
Jalan Pattimura (Metro)
5°6'13.92" / 105°17'53.25"
5°4'51.23" / 105°17'0.75"
Metro
20
18
14.222
Metro - Kota Gajah
5°4'51.23" / 105°17'0.75"
4°58'45.25" / 105°19'26.46"
Lampung Tengah
21
19
29.450
Kota Gajah - Sp. Randu
4°58'45.25" / 105°19'26.46"
4°49'46.09" / 105°29'25.41"
Lampung Tengah
22
20
24.700
Sp. Randu - Seputih Surabaya
4°49'46.09" / 105°29'25.41"
4°41'4.74" / 105°37'49.60"
Lampung Tengah
23
21
24.230
Seputih Surabaya - Sadewa
4°41'4.74" / 105°37'49.60"
4°39'14.48" / 105°48'4.04"
Lampung Tengah
24
22
37.016
Bandar Jaya - Sp. Mandala
4°56'1.74" / 105°12'43.89"
4°43'27.15" / 105°24'42.57"
Lampung Tengah
25
23
13.984
Gunung Sugih - Kota Gajah
4°58'31.69" / 105°12'47.36"
4°58'45.25" / 105°19'26.46"
Lampung Tengah
26
24
15.065
Kota Gajah - Gedong Dalem
4°58'45.25" / 105°19'26.46"
5°2'37.99" / 105°25'34.53"
Lampung Tengah
27
25
14.086
Kalirejo - Bangunrejo
5°13'39.20" / 104°57'37.19"
5°8'19.87" / 105°2'15.43"
Lampung Tengah
28
26
22.212
Bangunrejo - Wates
5°8'19.87" / 105°2'15.43"
5°5'53.17" / 105°11'25.23"
Lampung Tengah
29
27
12.415
Wates - Metro
5°5'53.17" / 105°11'25.23"
5°6'41.40" / 105°17'12.87"
Lampung Tengah
30
28
1.191
Jalan Brigjen. Katamso (Metro)
5°6'41.40" / 105°17'12.87"
5°7'18.01" / 105°17'24.74"
Metro
31
29
30.500
Gunung Sugih - Padang Ratu
4°58'31.69" / 105°12'47.36"
5°3'0.45" / 104°58'5.24"
Lampung Tengah
32
30
24.796
Padang Ratu - Pekurun Udik
5°3'0.45" / 104°58'5.24"
4°58'42.66" / 104°49'17.00"
Lampung Tengah
33
31
12.560
Pekurun Udik - Aji Kagungan
4°58'42.66" / 104°49'17.00"
4°53'28.90" / 104°48'57.48"
Lampung Utara
34
32
22.603
Padang Ratu - Kalirejo
5°3'0.45" / 104°58'5.24"
5°13'39.20" / 104°57'37.19"
Lampung Tengah
35
33
16.392
Kalirejo - Pringsewu
5°13'39.20" / 104°57'37.19"
5°21'21.86" / 104°58'37.34"
Pringsewu
36
34
18.797
Pringsewu - Pardasuka
5°21'21.86" / 104°58'37.34"
5°28'46.56" / 104°55'45.09"
Pringsewu
37
35
5.058
Pardasuka - Sukamara
5°28'46.56" / 104°55'45.09"
5°29'12.76" / 104°53'27.77"
Pringsewu
38
36
21.777
Sukamara - Kuripan
5°29'12.76" / 104°53'27.77"
5°35'54.36" / 104°48'37.64"
Tanggamus
39
37
24.124
Branti - Gedong Tataan
5°14'22.63" / 105°10'22.79"
5°22'46.91" / 105°5'41.75"
Pesawaran
40
38
16.666
Gedong Tataan - Kedondong
5°22'46.91" / 105°5'41.75"
5°28'14.43" / 105°0'0.11"
Pesawaran
41
39
11.092
Kedondong - Pardasuka
5°28'14.43" / 105°0'0.11"
5°28'46.56" / 104°55'45.09"
Pesawaran
42
40
29.671
Padang Cermin - Kedondong
5°35'59.24" / 105°8'33.15"
5°28'14.43" / 105°0'0.11"
Pesawaran
43
41
191
Jalan Tenggiri (Bandar Lampung)
5°26'58.68" / 105°15'42.38"
5°27'4.90" / 105°15'41.75"
Bandar Lampung
44
41
5.873
Jalan R.E. Martadinata (Bandar Lampung)
5°27'4.90" / 105°15'41.75"
5°29'27.51" / 105°15'1.62"
Bandar Lampung
45
42
29.157
Lempasing - Padang Cermin
5°29'27.51" / 105°15'1.62"
5°35'59.24" / 105°8'33.15"
Pesawaran
46
43
31.732
Padang Cermin - Sp. Teluk Kiluan
5°35'59.24" / 105°8'33.15"
5°45'24.07" / 105°7'15.79"
Pesawaran
47
44
25.157
Sp. Teluk Kiluan - Sp. Umbar
5°45'24.07" / 105°7'15.79"
5°41'40.62" / 104°59'42.37"
Tanggamus
48
45
20.000
Sp. Umbar - Putih Doh
5°41'40.62" / 104°59'42.37"
5°38'50.97" / 104°52'48.55"
Tanggamus
49
46
11.736
Putih Doh - Kuripan
5°38'50.97" / 104°52'48.55"
5°35'54.36" / 104°48'37.64"
Tanggamus
50
47
22.215
Kuripan - Sp. Kota Agung
5°35'54.36" / 104°48'37.64"
5°30'24.42" / 104°40'50.26"
Tanggamus
51
48
33.628
Pekon Balak - Suoh
5°0'51.70" / 104°9'47.57"
5°18'45.50" / 104°19'45.38"
Lampung Barat
52
49
30.447
Suoh - Sp. Blok 9
5°18'45.50" / 104°19'45.38"
5°18'50.64" / 104°23'31.20"
Lampung Barat
53
50
20.873
Sp. Blok 9 - Sanggi
5°18'50.64" / 104°23'31.20"
5°26'44.48" / 104°27'53.42"
Tanggamus
54
51
5.179
Jalan Raden Intan (Liwa)
5°2'12.33" / 104°4'56.71"
5°0'17.70" / 104°2'34.48"
Lampung Barat
55
52
19.109
Liwa - Bts. Sumatera Selatan
5°0'17.70" / 104°2'34.48"
4°54'17.09" / 104°0'43.62"
Lampung Barat
56
53
1.812
Jalan Adam Malik (Krui)
5°9'2.15" / 103°56'58.44"
5°9'16.04" / 103°56'15.45"
Pesisir Barat
57
54
1.810
Krui - Pekon Serai
5°11'56.23" / 103°55'47.56"
5°12'36.80" / 103°56'13.27"
Pesisir Barat
58
55
8.374
Kotajawa - Kampung Baru
5°36'32.14" / 104°20'48.48"
5°37'59.97" / 104°18'5.81"
Pesisir Barat
59
56
35.600
Talang Padang - Ngarip
5°21'53.77" / 104°46'57.75"
5°18'31.34" / 104°33'42.93"
Tanggamus
60
57
21.500
Ngarip - Ulu Semong
5°18'31.34" / 104°33'42.93"
5°12'49.05" / 104°26'46.90"
Tanggamus
61
58
9.660
Ulu Semong - Sp. Trimulyo
5°12'49.05" / 104°26'46.90"
5°8'46.07" / 104°27'53.87"
Tanggamus
62
59
24.574
Sp. Trimulyo - Bungin - Sp. Tugu Sari
5°8'46.07" / 104°27'53.87"
5°0'24.23" / 104°29'19.79"
Lampung Barat
63
60
8.000
Tekad - Batutegi
5°19'15.24" / 104°44'38.36"
5°16'55.22" / 104°45'48.68"
Tanggamus
64
61
416
Jalan Abung Raya Barat (Kotabumi)
4°49'21.10" / 104°52'39.08"
4°49'9.83" / 104°52'31.78"
Lampung Utara
65
61
823
Jalan Abung Raya Timur (Kotabumi)
4°49'9.83" / 104°52'31.78"
4°49'3.07" / 104°52'57.28"
Lampung Utara
66
62
19.725
Kotabumi - Bandar Abung
4°49'3.07" / 104°52'57.28"
4°42'42.12" / 104°59'42.62"
Lampung Utara
67
63
7.957
Bandar Abung - Bandar Sakti
4°42'42.12" / 104°59'42.62"
4°40'12.56" / 105°2'24.09"
Lampung Utara
68
64
10.099
Bandar Sakti - Sp. Daya Murni
4°40'12.56" / 105°2'24.09"
4°37'24.51" / 105°6'24.14"
Tulang Bawang Barat
69
65
12.389
Sp. Daya Murni - Gunung Batin
4°37'24.51" / 105°6'24.14"
4°38'15.54" / 105°12'20.31"
Tulang Bawang Barat
70
66
10.807
Bandar Abung - Sp. Tujok
4°42'42.12" / 104°59'42.62"
4°37'32.10" / 104°58'18.97"
Lampung Utara
71
67
35.698
Negara Ratu - Sp. Tujok
4°39'17.18" / 104°44'18.72"
4°37'32.10" / 104°58'18.97"
Lampung Utara
72
68
25.813
Sp. Tujok - Panaragan Jaya
4°37'32.10" / 104°58'18.97"
4°31'19.44" / 105°5'20.79"
Tulang Bawang Barat
73
69
4.104
Panaragan Jaya - Sp. Panaragan
4°31'19.44" / 105°5'20.79"
4°29'22.01" / 105°5'46.26"
Tulang Bawang Barat
74
70
17.478
Kotabumi - Ketapang
4°49'3.07" / 104°52'57.28"
4°43'11.44" / 104°47'52.58"
Lampung Utara
75
71
12.336
Ketapang - Negara Ratu
4°43'11.44" / 104°47'52.58"
4°39'17.18" / 104°44'18.72"
Lampung Utara
76
72
21.066
Negara Ratu - Gunung Betuah
4°39'17.18" / 104°44'18.72"
4°41'51.63" / 104°37'35.28"
Way Kanan
77
73
12.420
Gunung Betuah - Gunung Labuhan
4°41'51.63" / 104°37'35.28"
4°47'2.81" / 104°34'36.94"
Way Kanan
78
74
17.950
Sp. Empat - Kasui
4°34'55.87" / 104°30'12.92"
4°42'49.73" / 104°26'59.13"
Way Kanan
79
75
26.295
Kasui - Air Ringkih (Bts. Sumsel)
4°42'49.73" / 104°26'59.13"
4°40'42.34" / 104°17'25.86"
Way Kanan
80
76
11.063
Sp. Empat - Blambangan Umpu
4°34'55.87" / 104°30'12.92"
4°30'9.75" / 104°31'27.73"
Way Kanan
81
77
20.421
Blambangan Umpu - Sri Rejeki
4°30'9.75" / 104°31'27.73"
4°24'25.42" / 104°36'41.71"
Way Kanan
82
78
18.660
Sri Rejeki - Pakuan Ratu
4°24'25.42" / 104°36'41.71"
4°20'28.32" / 104°42'46.18"
Way Kanan
83
79
25.595
Pakuan Ratu - Bumi Harjo
4°20'28.32" / 104°42'46.18"
4°14'54.70" / 104°34'49.08"
Way Kanan
84
80
30.614
Bumi Harjo - Sp. Way Tuba
4°14'54.70" / 104°34'49.08"
4°23'33.91" / 104°24'53.77"
Way Kanan
85
81
15.501
Negara Ratu - Sp. Soponyono
4°39'17.18" / 104°44'18.72"
4°32'23.22" / 104°45'19.93"
Lampung Utara
86
82
10.858
Sp. Soponyono - Serupa Indah
4°32'23.22" / 104°45'19.93"
4°26'47.31" / 104°45'4.27"
Way Kanan
87
83
16.423
Serupa Indah - Pakuan Ratu
4°26'47.31" / 104°45'4.27"
4°20'28.32" / 104°42'46.18"
Way Kanan
88
84
11.200
Serupa Indah - Tajab
4°26'47.31" / 104°45'4.27"
4°23'41.56" / 104°49'22.51"
Way Kanan
89
85
1.865
Jln. Raya Gunung Sakti (Menggala)
4°29'48.11" / 105°14'27.41"
4°28'53.28" / 105°14'12.11"
Tulang Bawang
90
86
12.432
Bujung Tenuk - Penumangan
4°28'53.28" / 105°14'12.11"
4°29'43.45" / 105°8'14.99"
Tulang Bawang Barat
91
87
15.839
Penumangan - Tegal Mukti
4°29'43.45" / 105°8'14.99"
4°27'16.39" / 105°1'29.75"
Tulang Bawang Barat
92
88
36.000
Tegal Mukti - Tajab
4°27'16.39" / 105°1'29.75"
4°23'41.56" / 104°49'22.51"
Way Kanan
93
89
23.600
Tajab - Adijaya
4°23'41.56" / 104°49'22.51"
4°20'26.16" / 104°57'37.74"
Way Kanan
94
90
38.130
Adijaya - Tulung Randu
4°20'26.16" / 104°57'37.74"
4°21'23.69" / 105°13'51.64"
Tulang Bawang Barat
95
91
23.562
Penumangan - Unit VI
4°29'43.45" / 105°8'14.99"
4°19'58.35" / 105°9'27.68"
Tulang Bawang Barat
96
92
22.050
Sp. Unit VIII - Gedong Aji
4°24'13.05" / 105°15'31.14"
4°18'54.84" / 105°21'32.58"
Tulang Bawang
97
93
31.500
Gedong Aji - Umbul Mesir
4°18'54.84" / 105°21'32.58"
4°13'8.21" / 105°35'48.92"
Tulang Bawang
98
94
11.602
Sp. Pematang - Brabasan
4°1'35.39" / 105°14'52.49"
3°59'27.07" / 105°20'15.58"
Mesuji
99
95
29.443
Brabasan - Wiralaga
3°59'27.07" / 105°20'15.58"
3°50'19.95" / 105°29'10.36"
Mesuji
TEXT;

        $rows = $this->parseRows($raw);
        foreach ($rows as $row) {
            [$namaRuas, $kecamatan] = $this->extractKecamatanFromNama($row['nama_ruas']);
            $kabupaten = $this->normalizeKabupatenKota($row['kabupaten']);
            $kondisi = collect(Road::kondisiOptions())->random();

            $prioritas = $kondisi === Road::KONDISI_RUSAK_BERAT ? 'Rekonstruksi' : collect($prioritasList)->random();

            $awal = $this->parseLatLngPair($row['awal']);
            $akhir = $this->parseLatLngPair($row['akhir']);
            if ($awal === null || $akhir === null) {
                continue;
            }

            $payload = [
                'kabupaten' => $kabupaten,
                'kecamatan' => $kecamatan ?: 'Tidak diketahui',
                'panjang' => $this->normalizePanjangKm($row['panjang_km']),
                'lebar' => null,
                'kondisi' => $kondisi,
                'jenis_kerusakan' => $kondisi === Road::KONDISI_BAIK ? null : collect($jenisKerusakanList)->random(),
                'prioritas' => $prioritas,
                'tahun' => 2026,
                'foto' => null,
                'geometry' => json_encode([$awal, $akhir]),
            ];

            $existing = Road::query()->where('nama_ruas', $namaRuas)->first();
            if ($existing) {
                $existing->update($payload);
                continue;
            }

            Road::create(['nama_ruas' => $namaRuas] + $payload);
        }
    }

    private function parseRows(string $raw): array
    {
        $lines = preg_split('/\R/u', $raw) ?: [];
        $lines = array_values(array_filter(array_map('trim', $lines), fn ($l) => $l !== ''));

        $rows = [];
        for ($i = 0; $i + 6 < count($lines); $i += 7) {
            $rows[] = [
                'no' => $lines[$i],
                'nomor_ruas' => $lines[$i + 1],
                'panjang_km' => $lines[$i + 2],
                'nama_ruas' => $lines[$i + 3],
                'awal' => $lines[$i + 4],
                'akhir' => $lines[$i + 5],
                'kabupaten' => $lines[$i + 6],
            ];
        }

        return $rows;
    }

    private function extractKecamatanFromNama(string $nama): array
    {
        $nama = trim($nama);
        if (preg_match('/\(([^)]+)\)\s*$/u', $nama, $m)) {
            $kecamatan = trim((string) $m[1]);
            $clean = trim((string) preg_replace('/\s*\([^)]+\)\s*$/u', '', $nama));
            return [$clean, $kecamatan];
        }
        return [$nama, 'Tidak diketahui'];
    }

    private function normalizeKabupatenKota(string $raw): string
    {
        $name = trim($raw);
        if ($name === '') {
            return 'Tidak diketahui';
        }

        if (preg_match('/^(Kota|Kabupaten)\s+/iu', $name)) {
            return preg_replace('/\s+/u', ' ', $name) ?: $name;
        }

        $city = [
            'Bandar Lampung' => 'Kota Bandar Lampung',
            'Metro' => 'Kota Metro',
        ];

        if (isset($city[$name])) {
            return $city[$name];
        }

        return 'Kabupaten ' . (preg_replace('/\s+/u', ' ', $name) ?: $name);
    }

    private function normalizePanjangKm(string $raw): float
    {
        $s = trim($raw);
        $hasDecimal = str_contains($s, '.') || str_contains($s, ',');
        $n = str_replace(',', '.', preg_replace('/[^0-9\.\-]/', '', $s) ?? '');
        $val = $n !== '' ? (float) $n : 0.0;

        if (!$hasDecimal && $val > 100) {
            $val = $val / 1000;
        }

        return round($val, 2);
    }

    private function parseLatLngPair(string $raw): ?array
    {
        $parts = array_map('trim', preg_split('/\s*\/\s*/u', $raw) ?: []);
        if (count($parts) < 2) {
            return null;
        }

        $lat = $this->parseAngle($parts[0], true);
        $lng = $this->parseAngle($parts[1], false);
        if ($lat === null || $lng === null) {
            return null;
        }

        return [round($lat, 6), round($lng, 6)];
    }

    private function parseAngle(string $raw, bool $isLat): ?float
    {
        $s = trim($raw);
        if ($s === '') {
            return null;
        }

        $s = str_replace(',', '.', $s);
        $sign = 1.0;

        if (preg_match('/[SW]/i', $s)) {
            $sign = -1.0;
        }
        if (preg_match('/^\s*-/', $s)) {
            $sign = -1.0;
        }

        if (preg_match('/(-?\d+(?:\.\d+)?)\s*°\s*(?:(\d+(?:\.\d+)?)\s*\'\s*)?(?:(\d+(?:\.\d+)?)\s*")?/u', $s, $m)) {
            $deg = (float) $m[1];
            $min = isset($m[2]) ? (float) $m[2] : 0.0;
            $sec = isset($m[3]) ? (float) $m[3] : 0.0;
            $decimal = abs($deg) + ($min / 60) + ($sec / 3600);

            if ($sign > 0 && $isLat) {
                $sign = -1.0;
            }

            return $decimal * $sign;
        }

        $num = preg_replace('/[^0-9\.\-]/', '', $s) ?? '';
        if ($num === '' || !is_numeric($num)) {
            return null;
        }
        $decimal = (float) $num;

        if ($decimal >= 0 && $isLat) {
            $decimal = -$decimal;
        }

        return $decimal;
    }
}
