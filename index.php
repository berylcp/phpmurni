<?php
// Mulai session
session_start();

// Kalau user belum login balikkan user ke halaman login
if( !isset($_SESSION["login"]) ) {
    header("Location: login.php");
    exit;
}

// Ambil data dari 'functions.php'
require 'functions.php';

// Ambil data dari tabel mahasiswa
$mahasiswa = query("SELECT * FROM mahasiswa");

// Jika tombol cari di tekan
if ( isset($_POST["cari"]) ) {
    $mahasiswa = cari($_POST["keyword"]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Admin</title>

    <style>
        .loader {
            width: 100px;
            position: absolute;
            top: 102.5px;
            left: 300px;
            z-index: -1;
            display: none;
        }
    </style>
</head>
<body>
    <a href="logout.php">Logout</a>


    <h1>Daftar Mahasiswa</h1>

    <a href="tambah.php">Tambah data mahasiswa</a>

    <br>
    <br>

    <form action="" method="post">
        <input type="text" name="keyword" size="40" placeholder="Masukkan keyword pencarian" autocomplete="off" autofocus id="keyword">

        <button type="submit" name="cari" id="tombol-cari">Cari</button>

        <img src="img/loader.gif" alt="loader" class="loader">

    </form>

    <br>

    <div class="container" id="container">
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>No.</th>
            <th>Aksi</th>
            <th>Gambar</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Prodi</th>
            <th>Email</th>
        </tr>

        <?php $i = 1; ?>
        <?php foreach( $mahasiswa as $row ) : ?>
        <tr>
            <td><?= $i; ?></td>
            <td>
                <a href="ubah.php?id=<?= $row["id"]; ?>">Ubah</a> 
                | 
                <a href="hapus.php?id=<?= $row["id"]; ?>" onclick="return confirm('yakin?');">hapus</a>
            </td>
            <td><img src="img/<?= $row["gambar"]; ?>" alt="<?= $row["nama"]; ?>" width="50"></td>
            <td><?= $row["nim"]; ?></td>
            <td><?= $row["nama"]; ?></td>
            <td><?= $row["prodi"]; ?></td>
            <td><?= $row["email"]; ?></td>
        </tr>
        <?php $i++; ?>
        <?php endforeach; ?>
    </table>
    </div>

    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>