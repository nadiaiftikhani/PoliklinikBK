<?php
// Fungsi Registrasi
function registrasi($data_form)
{
    global $conn;

    $username = strtolower(stripslashes($data_form["username"]));
    $password = mysqli_real_escape_string($conn, $data_form["password"]);
    $nama = mysqli_real_escape_string($conn, $data_form["nama"]);
    $alamat = mysqli_real_escape_string($conn, $data_form["alamat"]);
    $no_ktp = mysqli_real_escape_string($conn, $data_form["no_ktp"]);
    $no_hp = mysqli_real_escape_string($conn, $data_form["no_hp"]);
    $id_dokter = mysqli_real_escape_string($conn, $data_form["id_dokter"]);

    // Cek apakah username sudah ada
    $result = mysqli_query($conn, "SELECT username FROM pasien WHERE username = '$username'");
    if (mysqli_fetch_assoc($result)) {
        echo "<script>alert('Username sudah terdaftar!');</script>";
        return false;
    }

    // Enkripsi password
    // $password = password_hash($password, PASSWORD_DEFAULT);

    // Mendapatkan tahun dan bulan saat ini untuk format no_rm
    $tahun_bulan = date("Ym"); // Format: YYYYMM

    // Mengambil nomor RM terakhir untuk bulan ini
    $result = mysqli_query($conn, "SELECT MAX(CAST(SUBSTRING(no_rm, 8) AS SIGNED)) AS max_no_rm FROM pasien WHERE LEFT(no_rm, 6) = '$tahun_bulan'");
    $row = mysqli_fetch_assoc($result);
    $max_no_rm = $row['max_no_rm'];

    // Membuat nomor RM baru
    $new_no_rm = ($max_no_rm === null) ? "$tahun_bulan-1" : "$tahun_bulan-" . ($max_no_rm + 1);

    // Tambahkan user baru ke database dengan nomor RM baru
    $query = "INSERT INTO pasien (username, password, nama, alamat, no_ktp, no_hp, no_rm, id_dokter) 
              VALUES ('$username', '$password', '$nama', '$alamat', '$no_ktp', '$no_hp', '$new_no_rm', '$id_dokter')";

    $insert = mysqli_query($conn, $query);

    if (!$insert) {
        echo "<script>alert('Registrasi gagal: " . mysqli_error($conn) . "');</script>";
        return false;
    }

    return mysqli_affected_rows($conn);
}


// Fungsi Tambah Daftar Poli
function tambah_poli($data_form)
{
    global $conn;

    // Ambil data dari tiap elemen dalam form
    $id_pasien = htmlspecialchars($data_form["id_pasien"]);
    $id_jadwal = htmlspecialchars($data_form["id_jadwal"]);
    $keluhan = htmlspecialchars($data_form["keluhan"]);
    $status_periksa = htmlspecialchars($data_form["status_periksa"]);

    // Query insert data (tanpa kolom ID jika AUTO_INCREMENT)
    $query = "INSERT INTO daftar_poli (id_pasien, id_jadwal, keluhan,no_antrian, status_periksa) 
              VALUES ('$id_pasien', '$id_jadwal', '$keluhan',0, '$status_periksa')";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "<script>alert('Gagal menambahkan data: " . mysqli_error($conn) . "');</script>";
        return false;
    }

    return mysqli_affected_rows($conn);
}

?>
