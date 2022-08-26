-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 06 Mei 2020 pada 19.51
-- Versi Server: 10.1.29-MariaDB
-- PHP Version: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sipas`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `disposisi_notdis`
--

CREATE TABLE `disposisi_notdis` (
  `id_disposisi_notdis` int(11) NOT NULL,
  `id_surat_notdis` int(11) NOT NULL,
  `no_agenda` varchar(150) NOT NULL,
  `id_pengirim_disposisi` int(11) NOT NULL,
  `id_penerima_disposisi` int(11) NOT NULL,
  `tgl_dikirim_disposisi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tgl_dibaca_disposisi` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `isi_disposisi_notdis` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `disposisi_notdis`
--

INSERT INTO `disposisi_notdis` (`id_disposisi_notdis`, `id_surat_notdis`, `no_agenda`, `id_pengirim_disposisi`, `id_penerima_disposisi`, `tgl_dikirim_disposisi`, `tgl_dibaca_disposisi`, `isi_disposisi_notdis`) VALUES
(1, 2, 'MN-102100112', 9, 8, '2020-02-27 15:17:58', '2020-02-27 15:20:13', 'Dicoba yaaa, inii tolong dilaksanakan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `isi_surat_notdis`
--

CREATE TABLE `isi_surat_notdis` (
  `id_isi_surat_notdis` int(11) NOT NULL,
  `id_surat_notdis` int(11) NOT NULL,
  `isi_notdis` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `isi_surat_notdis`
--

INSERT INTO `isi_surat_notdis` (`id_isi_surat_notdis`, `id_surat_notdis`, `isi_notdis`) VALUES
(1, 1, 'wawa'),
(2, 2, 'cfcfcfc'),
(3, 3, 'awawa'),
(4, 4, 'awa'),
(5, 5, 'awa'),
(6, 6, 'awa'),
(7, 7, 'awawa');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kepada_surat_notdis`
--

CREATE TABLE `kepada_surat_notdis` (
  `id_kepada_surat_notdis` int(11) NOT NULL,
  `id_surat_notdis` int(11) NOT NULL,
  `kepada` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kepada_surat_notdis`
--

INSERT INTO `kepada_surat_notdis` (`id_kepada_surat_notdis`, `id_surat_notdis`, `kepada`) VALUES
(1, 1, 9),
(2, 2, 9),
(3, 3, 19),
(4, 4, 10),
(5, 5, 10),
(6, 6, 9),
(7, 7, 9);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kompartemen`
--

CREATE TABLE `kompartemen` (
  `id_kompartemen` int(11) NOT NULL,
  `nama_kompartemen` varchar(100) NOT NULL,
  `kepanjangan_kompartemen` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kompartemen`
--

INSERT INTO `kompartemen` (`id_kompartemen`, `nama_kompartemen`, `kepanjangan_kompartemen`) VALUES
(5, 'Dit Akademik', 'DIREKTORAT AKADEMIK'),
(6, 'Bag Renmin', 'BAGIAN RENCANA ADMINISTRASI'),
(7, 'Dit Bintarlat', 'DIREKTORAT PEMBINAAN TARUNA DAN LATIHAN');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasi_surat`
--

CREATE TABLE `notifikasi_surat` (
  `id_notifikasi` int(11) NOT NULL,
  `id_surat` int(11) NOT NULL,
  `id_pengirim_notif` int(11) NOT NULL,
  `id_penerima_notif` int(11) NOT NULL,
  `tgl_notif_kirim` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tgl_notif_baca` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `isi_notif` text NOT NULL,
  `is_read_notif` int(11) NOT NULL,
  `jenis_surat_notifikasi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `notifikasi_surat`
--

INSERT INTO `notifikasi_surat` (`id_notifikasi`, `id_surat`, `id_pengirim_notif`, `id_penerima_notif`, `tgl_notif_kirim`, `tgl_notif_baca`, `isi_notif`, `is_read_notif`, `jenis_surat_notifikasi`) VALUES
(1, 5, 23, 7, '2020-02-27 16:01:26', '0000-00-00 00:00:00', 'aa', 0, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelayanan`
--

CREATE TABLE `pelayanan` (
  `id_pelayanan` int(11) NOT NULL,
  `nama_pelayanan` varchar(100) NOT NULL,
  `kepanjangan_pelayanan` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `pelayanan`
--

INSERT INTO `pelayanan` (`id_pelayanan`, `nama_pelayanan`, `kepanjangan_pelayanan`) VALUES
(2, 'Taud', 'TATA USAHA DAN ADMINISTRASI'),
(3, 'Keuangan', 'KEUANGAN');

-- --------------------------------------------------------

--
-- Struktur dari tabel `persetujuan_notdis`
--

CREATE TABLE `persetujuan_notdis` (
  `id_persetujuan_notdis` int(11) NOT NULL,
  `id_surat_notdis` int(11) NOT NULL,
  `id_pengirim` int(11) NOT NULL,
  `id_penerima` int(11) NOT NULL,
  `nomer_surat_persetujuan_notdis` varchar(100) NOT NULL,
  `tanggal_dikirim` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tanggal_diacc` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status_persetujuan_notdis` enum('Diajukan','Diajukan dan Disetujui','Diajukan dan Ditolak','Disetujui','Ditolak','Menunggu') NOT NULL,
  `is_read` int(11) NOT NULL,
  `atas_surat_persetujuan_notdis` text NOT NULL,
  `nama_pegawai_persetujuan_notdis` varchar(150) NOT NULL,
  `pangkat_nrp_persetujuan_notdis` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `persetujuan_notdis`
--

INSERT INTO `persetujuan_notdis` (`id_persetujuan_notdis`, `id_surat_notdis`, `id_pengirim`, `id_penerima`, `nomer_surat_persetujuan_notdis`, `tanggal_dikirim`, `tanggal_diacc`, `status_persetujuan_notdis`, `is_read`, `atas_surat_persetujuan_notdis`, `nama_pegawai_persetujuan_notdis`, `pangkat_nrp_persetujuan_notdis`) VALUES
(1, 1, 7, 23, 'B/ND-1464/XI/REN4.4/2019/Renmin', '2020-02-24 04:48:03', '2020-02-24 04:48:03', 'Diajukan dan Disetujui', 1, 'KASIAK', 'SONY BIMANARA, S.IK, M.ENG', 'AKBP NRP 199981281121'),
(2, 1, 23, 8, 'B/ND-1464/XI/REN4.4/2019/Renmin', '2020-02-24 04:48:52', '2020-02-24 04:48:52', 'Disetujui', 1, 'SEKBAG BINDIK', 'INDRA HERYANTO, SS\r\n', 'KOMBES POL NRP 2121212112'),
(3, 1, 8, 22, 'B/ND-1464/XI/REN4.4/2019/Renmin', '2020-02-27 15:39:21', '2020-02-27 15:39:21', 'Disetujui', 1, 'KABAG BINDIK', 'KHAIRU NASRUDIN, SIK\r\n', 'KOMPOL NRP 2212121113'),
(4, 1, 22, 10, 'awawa', '2020-02-27 15:39:21', '0000-00-00 00:00:00', 'Menunggu', 1, 'SEKDIT AKADEMIK', 'MUNTAHA\r\n', 'KOMBES POL NRP 829899891'),
(5, 1, 10, 6, ' ', '2020-02-24 04:39:48', '0000-00-00 00:00:00', 'Menunggu', 0, 'KADIT AKADEMIK', 'MUHAMMAD ROSID RIDHO, SIK\r\n', 'KOMBES POL NRP 31313117'),
(6, 1, 6, 9, ' ', '2020-02-24 04:39:48', '0000-00-00 00:00:00', 'Menunggu', 0, 'KATAUD', 'FAJARHGL', 'KOMPOL NRP 221212111'),
(7, 2, 8, 22, 'B/ND-1464/XI/REN4.4/2019/Renmi', '2020-02-24 04:54:54', '2020-02-24 04:54:54', 'Diajukan dan Disetujui', 1, 'KABAG BINDIK', 'KHAIRU NASRUDIN, SIK\r\n', 'KOMPOL NRP 2212121113'),
(8, 2, 22, 10, 'B/ND-1464/XI/REN4.4/2019/Renmi', '2020-02-27 15:10:27', '2020-02-27 15:10:27', 'Disetujui', 1, 'SEKDIT AKADEMIK', 'MUNTAHA\r\n', 'KOMBES POL NRP 829899891'),
(9, 2, 10, 6, 'B/ND-1464/XI/REN4.4/2019/Renmi', '2020-02-27 15:15:11', '2020-02-27 15:15:11', 'Disetujui', 1, 'KADIT AKADEMIK', 'MUHAMMAD ROSID RIDHO, SIK\r\n', 'KOMBES POL NRP 31313117'),
(10, 2, 6, 9, 'B/ND-1464/XI/REN4.4/2019/Renmi', '2020-02-27 15:17:32', '2020-02-27 15:17:32', 'Disetujui', 1, 'KATAUD', 'FAJARHGL', 'KOMPOL NRP 221212111'),
(11, 3, 7, 23, 'B/ND-1464/XI/REN4.4/2019/Renmin', '2020-02-27 15:43:13', '2020-02-27 15:43:13', 'Diajukan dan Disetujui', 1, 'KASIAK', 'SONY BIMANARA, S.IK, M.ENG', 'AKBP NRP 199981281121'),
(12, 3, 23, 8, 'awa', '2020-02-27 15:43:13', '0000-00-00 00:00:00', 'Menunggu', 1, 'SEKBAG BINDIK', 'INDRA HERYANTO, SS\r\n', 'KOMBES POL NRP 2121212112'),
(13, 3, 8, 22, ' ', '2020-02-27 15:40:28', '0000-00-00 00:00:00', 'Menunggu', 0, 'KABAG BINDIK', 'KHAIRU NASRUDIN, SIK\r\n', 'KOMPOL NRP 2212121113'),
(14, 3, 22, 10, ' ', '2020-02-27 15:40:28', '0000-00-00 00:00:00', 'Menunggu', 0, 'SEKDIT AKADEMIK', 'MUNTAHA\r\n', 'KOMBES POL NRP 829899891'),
(15, 3, 10, 30, ' ', '2020-02-27 15:40:28', '0000-00-00 00:00:00', 'Menunggu', 0, 'KADIT AKADEMIK', 'MUHAMMAD ROSID RIDHO, SIK\r\n', 'KOMBES POL NRP 31313117'),
(16, 3, 30, 19, ' ', '2020-02-27 15:40:28', '0000-00-00 00:00:00', 'Menunggu', 0, 'SEKBAG RENMIN', 'DWI WIRA SAFITRI, SE, MM\r\n', 'KOMBES POL NRP 41418'),
(17, 4, 7, 23, 'B/ND-1464/XI/REN4.4/2019/Renmin', '2020-02-27 15:43:18', '2020-02-27 15:43:18', 'Diajukan dan Disetujui', 1, 'KASIAK', 'SONY BIMANARA, S.IK, M.ENG', 'AKBP NRP 199981281121'),
(18, 4, 23, 8, 'awa', '2020-02-27 15:43:18', '0000-00-00 00:00:00', 'Menunggu', 1, 'SEKBAG BINDIK', 'INDRA HERYANTO, SS\r\n', 'KOMBES POL NRP 2121212112'),
(19, 4, 8, 22, ' ', '2020-02-27 15:40:45', '0000-00-00 00:00:00', 'Menunggu', 0, 'KABAG BINDIK', 'KHAIRU NASRUDIN, SIK\r\n', 'KOMPOL NRP 2212121113'),
(20, 4, 22, 10, ' ', '2020-02-27 15:40:45', '0000-00-00 00:00:00', 'Menunggu', 0, 'SEKDIT AKADEMIK', 'MUNTAHA\r\n', 'KOMBES POL NRP 829899891'),
(21, 5, 7, 23, 'B/ND-1464/XI/REN4.4/2019/Renmi', '2020-02-27 16:01:26', '2020-02-27 16:01:26', 'Ditolak', 1, 'KASIAK', 'SONY BIMANARA, S.IK, M.ENG', 'AKBP NRP 199981281121'),
(22, 5, 23, 8, ' ', '2020-02-27 15:41:00', '0000-00-00 00:00:00', 'Menunggu', 0, 'SEKBAG BINDIK', 'INDRA HERYANTO, SS\r\n', 'KOMBES POL NRP 2121212112'),
(23, 5, 8, 22, ' ', '2020-02-27 15:41:00', '0000-00-00 00:00:00', 'Menunggu', 0, 'KABAG BINDIK', 'KHAIRU NASRUDIN, SIK\r\n', 'KOMPOL NRP 2212121113'),
(24, 5, 22, 10, ' ', '2020-02-27 15:41:01', '0000-00-00 00:00:00', 'Menunggu', 0, 'SEKDIT AKADEMIK', 'MUNTAHA\r\n', 'KOMBES POL NRP 829899891'),
(25, 6, 22, 10, 'B/ND-1464/XI/REN4.4/2019/Renmin', '2020-02-27 16:20:46', '2020-02-27 16:20:46', 'Diajukan dan Disetujui', 1, 'SEKDIT AKADEMIK', 'MUNTAHA\r\n', 'KOMBES POL NRP 829899891'),
(26, 6, 10, 6, 'awa', '2020-02-27 16:21:15', '2020-02-27 16:21:15', 'Disetujui', 1, 'KADIT AKADEMIK', 'MUHAMMAD ROSID RIDHO, SIK\r\n', 'KOMBES POL NRP 31313117'),
(27, 6, 6, 9, 'awa', '2020-02-27 16:21:15', '0000-00-00 00:00:00', 'Disetujui', 1, 'KATAUD', 'FAJARHGL', 'KOMPOL NRP 221212111'),
(28, 7, 22, 10, 'B/ND-1464/XI/REN4.4/2019/Renmin', '2020-02-27 16:22:53', '2020-02-27 16:22:53', 'Diajukan dan Disetujui', 1, 'SEKDIT AKADEMIK', 'MUNTAHA\r\n', 'KOMBES POL NRP 829899891'),
(29, 7, 10, 6, 'awa', '2020-02-27 16:23:07', '2020-02-27 16:23:07', 'Disetujui', 1, 'KADIT AKADEMIK', 'MUHAMMAD ROSID RIDHO, SIK\r\n', 'KOMBES POL NRP 31313117'),
(30, 7, 6, 9, 'awa', '2020-02-27 16:23:07', '0000-00-00 00:00:00', 'Disetujui', 1, 'KATAUD', 'FAJARHGL', 'KOMPOL NRP 221212111');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pimpinan`
--

CREATE TABLE `pimpinan` (
  `id_pimpinan` int(11) NOT NULL,
  `nama_pimpinan` varchar(100) NOT NULL,
  `kepanjangan_pimpinan` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `pimpinan`
--

INSERT INTO `pimpinan` (`id_pimpinan`, `nama_pimpinan`, `kepanjangan_pimpinan`) VALUES
(1, 'Gubernur', 'GUBERNUR AKADEMI KEPOLISIAN'),
(8, 'Wakil Gubernur', 'WAKIL GUBERNUR AKADEMI KEPOLISIAN');

-- --------------------------------------------------------

--
-- Struktur dari tabel `satuan_kerja`
--

CREATE TABLE `satuan_kerja` (
  `id_satuan_kerja` int(11) NOT NULL,
  `id_kompartemen` int(11) NOT NULL,
  `nama_satuan_kerja` varchar(100) NOT NULL,
  `kepanjangan_satuan_kerja` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `satuan_kerja`
--

INSERT INTO `satuan_kerja` (`id_satuan_kerja`, `id_kompartemen`, `nama_satuan_kerja`, `kepanjangan_satuan_kerja`) VALUES
(4, 5, 'Bag Bindik', 'BAGIAN PEMBINAAN DAN PENDIDIKAN'),
(5, 5, 'Korgadik', 'KORDINATOR TENAGA PENDIDIK'),
(13, 5, 'Bag Jarlat', 'BAGIAN PENGAJARAN LATIHAN'),
(14, 5, 'Bid Jas', 'BIDANG JASMANI'),
(15, 5, 'Bid Jemen', 'BIDANG MANAJEMEN'),
(16, 5, 'Bid Kum', 'BIDANG HUKUM'),
(17, 5, 'Bid Pensos', 'BIDANG PENELITIAN SOSIAL'),
(18, 5, 'Bid Proftek', 'BIDANG PROFESI DAN TEKNOLOGI'),
(20, 7, 'Bag Binlat', 'BAGIAN PEMBINAAN DAN LATIHAN'),
(21, 7, 'Bag Kermadian', 'BAGIAN KERJASAMA DAN PENGABDIAN'),
(22, 7, 'Bag Humas', 'BAGIAN HUBUNGAN MASYARAKAT'),
(23, 7, 'Korbintarsis', 'KORDINATOR PEMBINAAN TARUNA DAN SISWA'),
(24, 6, 'Bag Ren', 'BAGIAN PERENCANAAN'),
(25, 6, 'Bag Sumda', 'BAGIAN SUMBER DAYA'),
(26, 6, 'Bag Um', 'BAGIAN UMUM');

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat_nota_dinas`
--

CREATE TABLE `surat_nota_dinas` (
  `id_surat_notdis` int(11) NOT NULL,
  `dari` int(11) NOT NULL,
  `nomer_surat_notdis` varchar(50) NOT NULL,
  `perihal_notdis` text NOT NULL,
  `rujukan_notdis` text NOT NULL,
  `tanggal_surat_notdis` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `atas_surat_notdis` varchar(200) NOT NULL,
  `nama_pegawai_surat_notdis` varchar(150) NOT NULL,
  `pangkat_nrp_surat_notdis` varchar(150) NOT NULL,
  `id_pengirim_awal` int(11) NOT NULL,
  `nama_dokumen` varchar(255) NOT NULL,
  `status_surat` int(11) NOT NULL,
  `qr_code` varchar(200) NOT NULL,
  `last_genrated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `key_surat` char(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `surat_nota_dinas`
--

INSERT INTO `surat_nota_dinas` (`id_surat_notdis`, `dari`, `nomer_surat_notdis`, `perihal_notdis`, `rujukan_notdis`, `tanggal_surat_notdis`, `atas_surat_notdis`, `nama_pegawai_surat_notdis`, `pangkat_nrp_surat_notdis`, `id_pengirim_awal`, `nama_dokumen`, `status_surat`, `qr_code`, `last_genrated`, `key_surat`) VALUES
(1, 10, '', 'awawa', '<ol type=\"a\">\r\n <li>awawawa</li>\r\n <li>awawa</li>\r\n</ol>\r\n', '2020-02-24 04:42:59', 'KADIT AKADEMIK', 'MUHAMMAD ROSID RIDHO, SIK\r\n', 'KOMBES POL NRP 31313117', 7, '-', 0, '158251918747.png', '2020-02-24 04:42:59', '158251918747'),
(2, 10, 'B/ND-1464/XI/REN4.4/2019/Renmi', 'ccccc', '<ol type=\"a\">\r\n <li>ccccc</li>\r\n <li>cc</li>\r\n <li>cccc</li>\r\n</ol>\r\n', '2020-02-27 15:17:32', 'KADIT AKADEMIK', 'MUHAMMAD ROSID RIDHO, SIK\r\n', 'KOMBES POL NRP 31313117', 8, '-', 1, '158251962303.png', '0000-00-00 00:00:00', '158251962303'),
(3, 10, ' ', 'awawa', '<ol type=\"a\">\r\n <li>awaawa</li>\r\n <li>awawa</li>\r\n</ol>\r\n', '0000-00-00 00:00:00', 'KADIT AKADEMIK', 'MUHAMMAD ROSID RIDHO, SIK\r\n', 'KOMBES POL NRP 31313117', 7, '-', 0, '158281802727.png', '0000-00-00 00:00:00', '158281802727'),
(4, 8, ' ', 'awa', '<ol type=\"a\">\r\n <li>awawa</li>\r\n <li>awawa</li>\r\n</ol>\r\n', '0000-00-00 00:00:00', 'KABAG BINDIK', 'KHAIRU NASRUDIN, SIK\r\n', 'KOMPOL NRP 2212121113', 7, '-', 0, '158281804444.png', '0000-00-00 00:00:00', '158281804444'),
(5, 8, ' ', 'awa', '<ol type=\"a\">\r\n <li>awawa</li>\r\n <li>awa</li>\r\n</ol>\r\n', '0000-00-00 00:00:00', 'KABAG BINDIK', 'KHAIRU NASRUDIN, SIK\r\n', 'KOMPOL NRP 2212121113', 7, '-', 0, '158281806000.png', '0000-00-00 00:00:00', '158281806000'),
(6, 10, 'awa', 'awa', '<ol type=\"a\">\r\n <li>awawawa</li>\r\n <li>waawaa</li>\r\n</ol>\r\n', '2020-02-27 16:20:46', 'KADIT AKADEMIK', 'MUHAMMAD ROSID RIDHO, SIK\r\n', 'KOMBES POL NRP 31313117', 22, '-', 0, '158282042323.png', '0000-00-00 00:00:00', '158282042323'),
(7, 10, 'awa', 'awa', '<ol type=\"a\">\r\n <li>swawa</li>\r\n <li>awawaa</li>\r\n</ol>\r\n', '2020-02-27 16:22:53', 'KADIT AKADEMIK', 'MUHAMMAD ROSID RIDHO, SIK\r\n', 'KOMBES POL NRP 31313117', 22, '-', 0, '158282055838.png', '0000-00-00 00:00:00', '158282055838');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tembusan_kirim`
--

CREATE TABLE `tembusan_kirim` (
  `id_tembusan_kirim` int(11) NOT NULL,
  `id_surat` int(11) NOT NULL,
  `id_pengirim_tembusan` int(11) NOT NULL,
  `id_penerima_tembusan` int(11) NOT NULL,
  `tanggal_dikirim_tembusan` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tanggal_dibaca_tembusan` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `jenis_surat` int(11) NOT NULL,
  `is_dibaca` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tembusan_kirim`
--

INSERT INTO `tembusan_kirim` (`id_tembusan_kirim`, `id_surat`, `id_pengirim_tembusan`, `id_penerima_tembusan`, `tanggal_dikirim_tembusan`, `tanggal_dibaca_tembusan`, `jenis_surat`, `is_dibaca`) VALUES
(1, 1, 10, 14, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 0),
(2, 2, 10, 21, '2020-02-27 15:17:32', '0000-00-00 00:00:00', 1, 1),
(3, 3, 10, 13, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 0),
(4, 4, 8, 13, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 0),
(5, 5, 8, 13, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 0),
(6, 6, 10, 14, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 0),
(7, 7, 10, 13, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `unit_kerja`
--

CREATE TABLE `unit_kerja` (
  `id_unit_kerja` int(11) NOT NULL,
  `id_satuan_kerja` int(11) NOT NULL,
  `nama_unit_kerja` varchar(100) NOT NULL,
  `kepanjangan_unit_kerja` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `unit_kerja`
--

INSERT INTO `unit_kerja` (`id_unit_kerja`, `id_satuan_kerja`, `nama_unit_kerja`, `kepanjangan_unit_kerja`) VALUES
(3, 4, 'Siak', 'SISTEM INFORMASI AKADEMI KEPOLISIAN'),
(4, 4, 'Subbag Evadasi', 'SUBBAGIAN EVALUASI DAN VALIDASI'),
(5, 4, 'Subbag Mindik', 'SUBBAGIAN ADMINISTRASI PENDIDIKAN'),
(6, 4, 'Subbag Rendaldik', 'SUBBAGIAN RENMIN DAN PENDIDIKAN'),
(7, 22, 'Subbag Berita', 'SUBBAGIAN BERITA'),
(8, 22, 'Subbag Dokliput', 'SUBBAGIAN DOKUMENTASI DAN PELIPUTAN'),
(9, 5, 'Sub Korgadik', 'SUBBAGIAN KORDINASI PENDIDIKAN');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_user` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nrp` char(30) NOT NULL,
  `pangkat` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `id_divisi` int(11) NOT NULL,
  `is_tingkatan` int(11) NOT NULL,
  `jabatan` enum('kepala','sekretaris') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama_user`, `email`, `nrp`, `pangkat`, `level`, `id_divisi`, `is_tingkatan`, `jabatan`) VALUES
(6, 'kataud', '$2y$10$ybkp8gHam3esZf6B/CiHcOnLY.Rp2tMitbPFG3yTd66QetTzDT2vu', 'fajarhgl', 'fajarhgl3@gmai.com', '221212111', 'Kompol', 0, 2, 2, 'kepala'),
(7, 'siak', '$2y$10$bRurVkKREiJB/fGzbGSH3u4NlgXHCFE5Xk/ptW.9QG03gebWggrxO', 'SONY BIMANARA, S.IK, M.Eng', 'fajarhdytt30@gmail.com', '199981281121', 'AKBP', 0, 3, 5, 'kepala'),
(8, 'bag_bindik', '$2y$10$Yxay0Y.9l8tZ45jQSYxDOOlHWwb0Vg/3y2mLXndPLWS8oWO4/CkJ2', 'KHAIRU NASRUDIN, SIK\r\n', 'bag_bindik@gmail.com', '2212121113', 'Kompol', 0, 4, 4, 'kepala'),
(9, 'gubernur', '$2y$10$t8AldnMxx/Mf6POqUQXwUumdlrfa957sS.cmz41b1vCpok/bhP1NC', 'Drs. FIANDAR\r\n', 'gubernur@gmail.com', '221212114', 'Irjen Pol', 0, 1, 1, 'kepala'),
(10, 'dir_akademik', '$2y$10$.MIpQKmlb.qxQvpeNYXc0OP39jEESeS/tzMCUNtoRttSE2LVMVTpO', 'MUHAMMAD ROSID RIDHO, SIK\r\n', 'dir_akademik@gmail.com', '31313117', 'Kombes Pol', 0, 5, 3, 'kepala'),
(11, 'admin', '$2y$10$hA8cU8dm.4qhcTSqzfW6Uuo0gL7mgfpMh1934mwhhE5xxLfYsmwL6', 'DAWUD, SE\r\n', 'admin@gmail.com', '2891829189821281', 'Admin', 1, 99, 99, 'kepala'),
(13, 'bag_ren', '$2y$10$dFXlmCNSwsONTvcIq6qPxeP8mlYNGrhTBhpgQRgb0vG5bwi300GUW', 'TATIT MUDJI WIDODO, SH, M.Si\r\n', 'bag_ren@gmail.com', '121', 'Kombes Pol', 0, 24, 4, 'kepala'),
(14, 'bag_binlat', '$2y$10$46intc/HXagvgrH/VYu.TOL.O.87imoPsUzQvY8b945Kl17Ri0fT2', 'TRI WAHYUNINGRUM, AMD\r\n', 'bag_binlat@gmail.com', '222', 'Kombes Pol', 0, 20, 4, 'kepala'),
(15, 'subag_berita', '$2y$10$CnOtGGhGS/6.TbXSWRuipeiW4t7W4Fr8RpnsvfHYHsgyvTlBsWW8m', 'EDI SUPODO, SH, MSI\r\n', 'subag_berita@gmail.com', '12121', 'Kombes Pol', 0, 7, 5, 'kepala'),
(16, 'subag_dokliput', '$2y$10$cN6VYZnZTgBARAANh0SSFuTJy1fsmQxTonUujdyYWNTkbToyDob4O', 'ENNY PURSITASARI, SE, MM\r\n', 'subag_dokliput@gmail.com', '113131', 'Kombes Pol', 0, 8, 5, 'kepala'),
(17, 'evadasi', '$2y$10$A4zOM6tJ.1wQBe3.lzsAnu9rNuxcwAvvrthLrxoVQaXk3ys/uhoBe', 'SRI MARYANI\r\n', 'evadasi@gmail.com', '1111111', 'Iptu', 0, 4, 5, 'kepala'),
(18, 'kor_gadik', '$2y$10$mnkxHsEQnHwXO8BJWuDXkOnFI3K3ftQ8xdArcRcv573xh8OvVi2yG', 'DWI MUHAR A, SPD\r\n', 'kor_gadik@gmail.com', '11', 'kor_gadik', 0, 5, 4, 'kepala'),
(19, 'bag_renmin', '$2y$10$pewzps/oB.JBZQXMD9LXp.IpvuY3AIDq5csajqJQy7uvipLyr7QTy', 'WAWAN KURNIAWAN, S.H., S.I.K., M.Si\r\n', 'bag_renmin@gmail.com', '55', 'Kombes Pol', 0, 6, 3, 'kepala'),
(20, 'dit_bintarlat', '$2y$10$90r2ILzCQvEtMISdiVdKKOsceEWJAosXG8CVMuY2KWGyWYYHCdAGu', 'ARY YUSWAN TRIONO, S.I.K.\r\n', 'dit_bintarlat@gmail.com', '8', 'Kombes Pol', 0, 7, 3, 'kepala'),
(21, 'bag_jarlat', '$2y$10$gdC6ZljvgodLZ0ArCmEgxuMDsQr7jtGYEAzhJJl3GBs.V3C4AretK', 'DEDY INDRIYANTO, S.I.K., M.Si.\r\n', 'bag_jarlat@gmail.com', '21211121', 'Kombes Pol', 0, 13, 4, 'kepala'),
(22, 'dir_akademik2', '$2y$10$X8RS07UgUlvcMC8a5C31X.Idat7.RC1NLuOOhr1GSbZoHnQLyLmS.', 'MUNTAHA\r\n', 'dir_akademik2@gmail.com', '829899891', 'Kombes Pol', 0, 5, 3, 'sekretaris'),
(23, 'bag_bindik2', '$2y$10$dUddT4kshbhMfj9TObfNZun3c23vcoa7yQK1dcgx07lxrUa77u/te', 'INDRA HERYANTO, SS\r\n', 'bag_bindik2@gmail.com', '2121212112', 'Kombes Pol', 0, 4, 4, 'sekretaris'),
(24, 'siak2', '$2y$10$3gFZK1cGmtrPl3/XERwwLu9cpUjMdMY6eavGC4LHd9cW3cwDB1/d2', 'TRIS LESMANA ZEVIANSYAH, S.H., S.I.K., M.H.\r\n', 'siak2@gmail.com', '2112', 'Akbp', 0, 3, 5, 'sekretaris'),
(25, 'bag_ren2', '$2y$10$4P1xhphZfPvISh2sakfXQeN9XyzK9gzUdX6QIxMBaMFCImxUf/Q2e', 'MUHAMMAD ISLAM AMARULLAH, S.I.K.\r\n', 'bag_ren2@gmail.com', '1242', 'Kombes Pol', 0, 24, 4, 'sekretaris'),
(26, 'bag_binlat2', '$2y$10$9Us1m9HFG.1.HFLGfos/ge895u6NtYUWaOtvGCgSOlWxgfZAQPQyG', 'RESTIANA PASARIBU, S.H., M.H.\r\n', 'bag_binlat2@gmail.com', '317', 'Kombes Pol', 0, 20, 4, 'sekretaris'),
(27, 'subag_berita2', '$2y$10$jS5u3EvmIfke6xT/wrTkneYrAw/0POjRyMdJLiXagJ13FYyoHP2Ry', 'NUNUK SETIYOWATI, SIK, MH\r\n', 'subag_berita2@gmail.com', '310', 'Kombes Pol', 0, 7, 5, 'sekretaris'),
(28, 'evadasi2', '$2y$10$.YAuozuws8W0iznF4uqfIuEcK0j0ZxIugflqgGC2SUwW0ZrHF8m3u', 'EKO SUHARTONO\r\n', 'evadasi2@gmail.com', '55', 'Kombes Pol', 0, 4, 5, 'sekretaris'),
(29, 'kor_gadik2', '$2y$10$mQoE7zQCW2M5GRbM6DeBQeG1jAjP3jluqO3D33/s/QZn1cIBj7aKa', 'I MADE ASTAWA, SH, MH\r\n', 'kor_gadik2@gmail.com', '414', 'Kombes Pol', 0, 5, 4, 'sekretaris'),
(30, 'bag_renmin2', '$2y$10$ACakiLJYoMKvAjeGyg9pwuYRAL9RVF0p9hlsH6CiPmtuWjMb.QcV2', 'DWI WIRA SAFITRI, SE, MM\r\n', 'bag_renmin2@gmail.com', '41418', 'Kombes Pol', 0, 6, 3, 'sekretaris'),
(31, 'dit_bintarlat2', '$2y$10$54BlbihvmYZkyP04b1zB3.De1ejSipBpA9LovDW6nPn50xzOSKl0C', 'HERWANA HASJIM, SS\r\n', 'dit_bintarlat2@gmail.com', '4141', 'Kombes Pol', 0, 7, 3, 'sekretaris'),
(32, 'bag_jarlat2', '$2y$10$M.fjMf1Gz2/enkqS9zgfsOyGwC6FT0mnf/xhUB0LK6csofFgEmV5O', 'SAPTO YUHANIS, SH, MH\r\n', 'bag_jarlat2@gmail.com', '3131', 'Kombes Pol', 0, 13, 4, 'sekretaris'),
(33, 'bag_humas', '$2y$10$x4guCz6O01CdqNXMLFk9HOYW0jI6wWRg9SFViMCd0fhPg.smc5xKi', 'HERWANA HASJIM, SS\r\n', 'bag_humas@gmail.com', '2121211', 'Kompol', 0, 22, 4, 'kepala'),
(34, 'bag_humas2', '$2y$10$ulg/qmxZPydII92lYH/lKewc8YU.34A.QPWHWnG8yfx1ePgdrjpNy', 'YUDIAR ALWAN MUNANDAR, S.TP., MS.i\r\n', 'bag_humas2@gmail.com', '1211219', 'Kombes Pol', 0, 22, 4, 'sekretaris'),
(35, 'mindik', '$2y$10$.TZFj0i2TOL8vtNBz3MAFeze0gRQ/IO8OgTQMKqi4JUR5ev3rMfYi', 'SUSYANTO, S.Sos\r\n', 'mindik@gmail.com', '1211107', 'Kombes Pol', 0, 5, 5, 'kepala'),
(36, 'mindik2', '$2y$10$zB3BDGGm9YTQu3bAkY5zBeHyhjz1gh03K/QNSMmbaxC3TWxbZSynm', 'FACHRUROZI, S.Ag\r\n', 'mindik2@gmail.com', '289182918982128112', 'Kompol', 0, 5, 5, 'sekretaris'),
(37, 'sub_korgadik', '$2y$10$jB2lJLviL/6OK3CUicS.6O338Zo/ECWyfv6z2wE2QtucwBbuiFHW2', 'BAMBANG SUMINTO, SH, MH\r\n', 'sub_korgadik@gmail.com', '212116', 'Kombes Pol', 0, 9, 5, 'kepala'),
(38, 'sub_korgadik2', '$2y$10$hFT0kz1mJLQlQv60WD1FtuW6D6IsY/EFzfEw58IM.MVBFg7E6Qoke', 'ANDI AZIZ, S.H.\r\n', 'sub_korgadik2@gmail.com', '11111110', 'Kompol', 0, 9, 5, 'sekretaris');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_token`
--

CREATE TABLE `user_token` (
  `id` int(11) NOT NULL,
  `email` varchar(128) NOT NULL,
  `token` varchar(128) NOT NULL,
  `date_created` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_token`
--

INSERT INTO `user_token` (`id`, `email`, `token`, `date_created`) VALUES
(1, 'fajarhdytt30@gmail.com', '+p8KvKuUwKi4hJ5aqDo08PpKXlAVtq3asd3VP1tHRZg=', 1574751777),
(2, 'fajarhdytt30@gmail.com', '6SHqhg+/0GOV3OkIfzQfxQlcP23bV8ZlnJQ0Yl7LhRY=', 1577638965),
(3, 'fajarhdytt30@gmail.com', 'jp5rBR2E0qD4cULaEocnu03zs4h4VXAfNgJJGb6w6mM=', 1580917769),
(4, 'fajarhdytt30@gmail.com', 'dvYu2j6toYlUx3Iik5R7231/Dv+wcPomWWrK8l3dbD4=', 1580917775),
(5, 'fajarhdytt30@gmail.com', 'uS3NXcFY4LW00zx4g79cs/OzC0IqWpuOeXoFihKR+NM=', 1580917865),
(6, 'fajarhdytt30@gmail.com', 'NzWoL4eYjYKVUAklSJaBm157GS6YiBRbd+BqOkitLzU=', 1580918120),
(7, 'fajarhdytt30@gmail.com', 'AUv0y5e6V6885kJ++yc1dwX3fTu68+oPiwPpx/v05xU=', 1580918430);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `disposisi_notdis`
--
ALTER TABLE `disposisi_notdis`
  ADD PRIMARY KEY (`id_disposisi_notdis`),
  ADD KEY `id_surat_notdis` (`id_surat_notdis`),
  ADD KEY `id_pengirim_disposisi` (`id_pengirim_disposisi`),
  ADD KEY `id_penerima_disposisi` (`id_penerima_disposisi`);

--
-- Indexes for table `isi_surat_notdis`
--
ALTER TABLE `isi_surat_notdis`
  ADD PRIMARY KEY (`id_isi_surat_notdis`),
  ADD KEY `id_surat_notdis` (`id_surat_notdis`);

--
-- Indexes for table `kepada_surat_notdis`
--
ALTER TABLE `kepada_surat_notdis`
  ADD PRIMARY KEY (`id_kepada_surat_notdis`),
  ADD KEY `id_surat_notdis` (`id_surat_notdis`),
  ADD KEY `kepada` (`kepada`);

--
-- Indexes for table `kompartemen`
--
ALTER TABLE `kompartemen`
  ADD PRIMARY KEY (`id_kompartemen`);

--
-- Indexes for table `notifikasi_surat`
--
ALTER TABLE `notifikasi_surat`
  ADD PRIMARY KEY (`id_notifikasi`),
  ADD KEY `id_pengirim_notif` (`id_pengirim_notif`),
  ADD KEY `id_penerima_notif` (`id_penerima_notif`);

--
-- Indexes for table `pelayanan`
--
ALTER TABLE `pelayanan`
  ADD PRIMARY KEY (`id_pelayanan`);

--
-- Indexes for table `persetujuan_notdis`
--
ALTER TABLE `persetujuan_notdis`
  ADD PRIMARY KEY (`id_persetujuan_notdis`),
  ADD KEY `id_surat_notdis` (`id_surat_notdis`),
  ADD KEY `id_pengirim` (`id_pengirim`),
  ADD KEY `id_penerima` (`id_penerima`);

--
-- Indexes for table `pimpinan`
--
ALTER TABLE `pimpinan`
  ADD PRIMARY KEY (`id_pimpinan`);

--
-- Indexes for table `satuan_kerja`
--
ALTER TABLE `satuan_kerja`
  ADD PRIMARY KEY (`id_satuan_kerja`),
  ADD KEY `id_kompartemen` (`id_kompartemen`);

--
-- Indexes for table `surat_nota_dinas`
--
ALTER TABLE `surat_nota_dinas`
  ADD PRIMARY KEY (`id_surat_notdis`),
  ADD KEY `dari` (`dari`),
  ADD KEY `id_pengirim_awal` (`id_pengirim_awal`);

--
-- Indexes for table `tembusan_kirim`
--
ALTER TABLE `tembusan_kirim`
  ADD PRIMARY KEY (`id_tembusan_kirim`),
  ADD KEY `id_pengirim_tembusan` (`id_pengirim_tembusan`),
  ADD KEY `id_penerima_tembusan` (`id_penerima_tembusan`);

--
-- Indexes for table `unit_kerja`
--
ALTER TABLE `unit_kerja`
  ADD PRIMARY KEY (`id_unit_kerja`),
  ADD KEY `id_satuan_kerja` (`id_satuan_kerja`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `user_token`
--
ALTER TABLE `user_token`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `disposisi_notdis`
--
ALTER TABLE `disposisi_notdis`
  MODIFY `id_disposisi_notdis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `isi_surat_notdis`
--
ALTER TABLE `isi_surat_notdis`
  MODIFY `id_isi_surat_notdis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kepada_surat_notdis`
--
ALTER TABLE `kepada_surat_notdis`
  MODIFY `id_kepada_surat_notdis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kompartemen`
--
ALTER TABLE `kompartemen`
  MODIFY `id_kompartemen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notifikasi_surat`
--
ALTER TABLE `notifikasi_surat`
  MODIFY `id_notifikasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pelayanan`
--
ALTER TABLE `pelayanan`
  MODIFY `id_pelayanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `persetujuan_notdis`
--
ALTER TABLE `persetujuan_notdis`
  MODIFY `id_persetujuan_notdis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `pimpinan`
--
ALTER TABLE `pimpinan`
  MODIFY `id_pimpinan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `satuan_kerja`
--
ALTER TABLE `satuan_kerja`
  MODIFY `id_satuan_kerja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tembusan_kirim`
--
ALTER TABLE `tembusan_kirim`
  MODIFY `id_tembusan_kirim` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `unit_kerja`
--
ALTER TABLE `unit_kerja`
  MODIFY `id_unit_kerja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `user_token`
--
ALTER TABLE `user_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `disposisi_notdis`
--
ALTER TABLE `disposisi_notdis`
  ADD CONSTRAINT `disposisi_notdis_ibfk_2` FOREIGN KEY (`id_penerima_disposisi`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `disposisi_notdis_ibfk_3` FOREIGN KEY (`id_pengirim_disposisi`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `disposisi_notdis_ibfk_4` FOREIGN KEY (`id_surat_notdis`) REFERENCES `surat_nota_dinas` (`id_surat_notdis`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `isi_surat_notdis`
--
ALTER TABLE `isi_surat_notdis`
  ADD CONSTRAINT `isi_surat_notdis_ibfk_1` FOREIGN KEY (`id_surat_notdis`) REFERENCES `surat_nota_dinas` (`id_surat_notdis`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kepada_surat_notdis`
--
ALTER TABLE `kepada_surat_notdis`
  ADD CONSTRAINT `kepada_surat_notdis_ibfk_2` FOREIGN KEY (`kepada`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kepada_surat_notdis_ibfk_3` FOREIGN KEY (`id_surat_notdis`) REFERENCES `surat_nota_dinas` (`id_surat_notdis`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `notifikasi_surat`
--
ALTER TABLE `notifikasi_surat`
  ADD CONSTRAINT `notifikasi_surat_ibfk_1` FOREIGN KEY (`id_penerima_notif`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notifikasi_surat_ibfk_2` FOREIGN KEY (`id_pengirim_notif`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `persetujuan_notdis`
--
ALTER TABLE `persetujuan_notdis`
  ADD CONSTRAINT `persetujuan_notdis_ibfk_2` FOREIGN KEY (`id_pengirim`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `persetujuan_notdis_ibfk_3` FOREIGN KEY (`id_penerima`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `persetujuan_notdis_ibfk_4` FOREIGN KEY (`id_surat_notdis`) REFERENCES `surat_nota_dinas` (`id_surat_notdis`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `satuan_kerja`
--
ALTER TABLE `satuan_kerja`
  ADD CONSTRAINT `satuan_kerja_ibfk_1` FOREIGN KEY (`id_kompartemen`) REFERENCES `kompartemen` (`id_kompartemen`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `surat_nota_dinas`
--
ALTER TABLE `surat_nota_dinas`
  ADD CONSTRAINT `surat_nota_dinas_ibfk_1` FOREIGN KEY (`dari`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `surat_nota_dinas_ibfk_2` FOREIGN KEY (`id_pengirim_awal`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tembusan_kirim`
--
ALTER TABLE `tembusan_kirim`
  ADD CONSTRAINT `tembusan_kirim_ibfk_1` FOREIGN KEY (`id_pengirim_tembusan`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tembusan_kirim_ibfk_2` FOREIGN KEY (`id_penerima_tembusan`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `unit_kerja`
--
ALTER TABLE `unit_kerja`
  ADD CONSTRAINT `unit_kerja_ibfk_1` FOREIGN KEY (`id_satuan_kerja`) REFERENCES `satuan_kerja` (`id_satuan_kerja`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
