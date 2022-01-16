<?php
// Hubungkan ke DBMS
$conn = mysqli_connect("localhost", "root", "", "phpdasar");

// Function untuk mengambil data dari tabel mahasiswa
function query($query) {
    // Gunakan variabel diluar function
    global $conn;

    // Ambil data dari tabel mahasiswa
    $result = mysqli_query($conn, $query);

    // Simpan data pada sebuah variabel yang berisi array kosong
    $rows = [];

    // Looping data
    while( $row = mysqli_fetch_assoc($result) ) {
        // Pisahkan data agar rapi
        $rows[] = $row;
    }
    return $rows;
}

// Function tambah
function tambah($data) {
    // Gunakan variabel diluar function
    global $conn;

    // Query data dari tiap elemen dalam form
    $nama = htmlspecialchars($data["nama"]);
    $nim = htmlspecialchars($data["nim"]);
    $prodi = htmlspecialchars($data["prodi"]);
    $email = htmlspecialchars($data["email"]);

    // Jalankan function upload gambar
    $gambar = upload();

    if( !$gambar ) {
        return false;
    }

     // Query insert data
     $query = "INSERT INTO mahasiswa VALUES ('', '$nama', '$nim', '$prodi', '$email', '$gambar')";

     mysqli_query($conn, $query);
 
     return mysqli_affected_rows($conn);
}

// Function upload
function upload() {
   $namaFile = $_FILES["gambar"]["name"];
   $ukuranFile = $_FILES["gambar"]["size"];
   $error = $_FILES["gambar"]["error"];
   $tmpName = $_FILES["gambar"]["tmp_name"];

    // cek apakah tidak ada gambar yang diupload
   if( $error === 4 ) {
       echo
       "
       <script>
            alert('Pilih gambar terlebih dahulu!')
       </script>
       ";
       return false;
   }

    // Cek apakah yang diupload adalah gambar
    $ekstensiGambarValid = ["jpg", "jpeg", "png"];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));

    if( !in_array($ekstensiGambar, $ekstensiGambarValid) ) {
        echo
       "
       <script>
            alert('Yang anda upload bukan gambar!')
       </script>
       ";
       return false;
    }

    // Cek jika ukurannya terlalu besar
    if( $ukuranFile > 1000000 ) {
        echo
       "
       <script>
            alert('Ukuran gambar terlalu besar!')
       </script>
       ";
       return false;
    }

    // Gambar siap diupload
    // Tambahkan nama gambar baru
    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar;

    move_uploaded_file($tmpName, 'img/' . $namaFileBaru);

    return $namaFileBaru;

}

// Function hapus
function hapus($id) {
    // Gunakan variabel diluar function
    global $conn;

    mysqli_query($conn, "DELETE FROM mahasiswa WHERE id = $id");

    return mysqli_affected_rows($conn);
}

// Function ubah
function ubah($data) {
    // Gunakan variabel diluar function
    global $conn;

    // Query data dari tiap elemen dalam form
    $id = $data["id"];
    $nama = htmlspecialchars($data["nama"]);
    $nim = htmlspecialchars($data["nim"]);
    $prodi = htmlspecialchars($data["prodi"]);
    $email = htmlspecialchars($data["email"]);
    $gambarLama = $data["gambarLama"];

    // Cek apakah user memilih gambar baru atau tidak
    if( $_FILES["gambar"]["error"] === 4 ) {
        $gambar = $gambarLama;
    } else {
        $gambar = upload();
    }

     // Query insert data
     $query = "UPDATE mahasiswa SET
                nama = '$nama',
                nim = '$nim',
                prodi = '$prodi',
                email = '$email',
                gambar = '$gambar' WHERE id = $id";

     mysqli_query($conn, $query);
 
     return mysqli_affected_rows($conn);
}

// Function cari
function cari($keyword) {
    $query = "SELECT * FROM mahasiswa WHERE 
                nama LIKE '%$keyword%' OR 
                nim LIKE '%$keyword%' OR
                prodi LIKE '%$keyword%' OR
                email LIKE '%$keyword%'";

    return query($query);
}

// Function daftar
function daftar($data) {
    // Gunakan variabel diluar function
    global $conn;

    $username = strtolower(stripslashes($data["username"]));
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password2 = mysqli_real_escape_string($conn, $data["password2"]);

    // Cek apakah username sudah ada atau belum
    $result = mysqli_query($conn, "SELECT username FROM user WHERE username = '$username'");

    if( mysqli_fetch_assoc($result) ) {
        echo
        "
        <script>
            alert('Username sudah ada!');
        </script>
        ";
        return false;
    }

    // Cek konfirmasi password
    if( $password !== $password2 ) {
        echo
        "
        <script>
            alert('Konfirmasi password tidak sesuai!');
        </script>
        ";
        return false;
    }

    // Enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Tambahkan user baru ke database
    mysqli_query($conn, "INSERT INTO user VALUES('', '$username', '$password')");

    return mysqli_affected_rows($conn);
}
?>