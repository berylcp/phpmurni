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

// Ambil id
$id = $_GET["id"];

if ( hapus($id) > 0 ) {
    echo "
        <script>
            alert('Data berhasil dihapus!');
            document.location.href = 'index.php';
        </script>
        ";
} else {
    echo "
        <script>
            alert('Data gagal dihapus!');
            document.location.href = 'index.php';
        </script>
        ";
}
?>