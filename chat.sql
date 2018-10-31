-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 29 Okt 2018 pada 04.58
-- Versi server: 10.1.30-MariaDB
-- Versi PHP: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chat`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat`
--

CREATE TABLE `chat` (
  `id_chat` int(25) NOT NULL,
  `waktu_mulai` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expired` datetime NOT NULL,
  `token` varchar(100) NOT NULL,
  `status` varchar(25) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nomor_telepon` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `chat`
--

INSERT INTO `chat` (`id_chat`, `waktu_mulai`, `expired`, `token`, `status`, `nama`, `email`, `nomor_telepon`) VALUES
(1, '2018-10-29 02:39:10', '2018-10-29 11:39:10', '68191a91242e2ad531668e267ca7514a', 'enabled', 'Saddam Sinatrya Jalu Mukti', 'saddamazyazy@outlook.com', '087763744268');

-- --------------------------------------------------------

--
-- Struktur dari tabel `config`
--

CREATE TABLE `config` (
  `id_config` int(25) NOT NULL,
  `name` text NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `config`
--

INSERT INTO `config` (`id_config`, `name`, `value`) VALUES
(1, 'status', 'online');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_chat`
--

CREATE TABLE `detail_chat` (
  `id_detail` int(25) NOT NULL,
  `id_chat` int(25) NOT NULL,
  `sumber` varchar(20) NOT NULL,
  `isi` text NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(25) NOT NULL DEFAULT 'unread'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `detail_chat`
--

INSERT INTO `detail_chat` (`id_detail`, `id_chat`, `sumber`, `isi`, `waktu`, `status`) VALUES
(1, 1, 'client', 'Can i get a witness?', '2018-10-29 02:39:10', 'read'),
(2, 1, 'admin', 'Yes, you could.', '2018-10-29 02:39:26', 'read'),
(3, 1, 'client', 'Will you be my witness', '2018-10-29 02:39:44', 'read'),
(4, 1, 'admin', 'Are you okay?', '2018-10-29 02:40:04', 'read');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mail`
--

CREATE TABLE `mail` (
  `id_mail` int(15) NOT NULL,
  `origin` int(15) NOT NULL,
  `dest` int(15) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `trash` enum('0','1') NOT NULL DEFAULT '0',
  `readed` enum('0','1') DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `mail`
--

INSERT INTO `mail` (`id_mail`, `origin`, `dest`, `title`, `content`, `date`, `trash`, `readed`) VALUES
(1, 1, 2, 'First Email To You', '<p>Hai! Terima kasih telah bergabung.</p>\r\n\r\n<blockquote>\r\n<p>Can i get a witness?</p>\r\n</blockquote>\r\n\r\n<p style=\"text-align: center;\">Center Text</p>\r\n\r\n<ol>\r\n	<li>List</li>\r\n	<li>List</li>\r\n	<li>List</li>\r\n</ol>\r\n\r\n<p><s>Coretan</s></p>\r\n', '2018-10-29 02:54:58', '0', '1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_mail`
--

CREATE TABLE `user_mail` (
  `id_user` int(15) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `nama` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_mail`
--

INSERT INTO `user_mail` (`id_user`, `username`, `password`, `role`, `nama`) VALUES
(1, 'admin', '$2y$10$ybpj37ZLkeZS7U6SL5a04Ojt.guP/e7uqntSR9k7NT9TRzkKf39lK', 'admin', 'Admin'),
(2, 'user', '$2y$10$zoucAk0XEFgegVlT9axNvOclhpNSEFoB5h54l2JCOrZrOZ.MFpBDK', 'user', 'Budi');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id_chat`);

--
-- Indeks untuk tabel `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id_config`);

--
-- Indeks untuk tabel `detail_chat`
--
ALTER TABLE `detail_chat`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_chat` (`id_chat`);

--
-- Indeks untuk tabel `mail`
--
ALTER TABLE `mail`
  ADD PRIMARY KEY (`id_mail`),
  ADD KEY `origin` (`origin`),
  ADD KEY `dest` (`dest`);

--
-- Indeks untuk tabel `user_mail`
--
ALTER TABLE `user_mail`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `chat`
--
ALTER TABLE `chat`
  MODIFY `id_chat` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `config`
--
ALTER TABLE `config`
  MODIFY `id_config` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `detail_chat`
--
ALTER TABLE `detail_chat`
  MODIFY `id_detail` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `mail`
--
ALTER TABLE `mail`
  MODIFY `id_mail` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `user_mail`
--
ALTER TABLE `user_mail`
  MODIFY `id_user` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_chat`
--
ALTER TABLE `detail_chat`
  ADD CONSTRAINT `detail_chat_ibfk_1` FOREIGN KEY (`id_chat`) REFERENCES `chat` (`id_chat`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
