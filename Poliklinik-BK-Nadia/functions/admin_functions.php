<?php 
// ==================== Fungsi Kelola Dokter ====================
// Fungsi Tambah Dokter
function tambah_dokter($data_form){
    global $conn;

     // Ambil data dari tiap elemen dalam form
    $nama = htmlspecialchars($data_form["nama"]);
    $alamat = htmlspecialchars($data_form["alamat"]);
    $no_hp = htmlspecialchars($data_form["no_hp"]);
    $nama_poli = htmlspecialchars($data_form["nama_poli"]);

    // Query insert data
    $query = "INSERT INTO dokter VALUES ('', '', '', '$nama', '$alamat','$no_hp', '$nama_poli')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// Fungsi Delete Dokter
function hapus_dokter($id){
    global $conn;
    mysqli_query($conn, "DELETE FROM dokter WHERE id = $id");
    
    return mysqli_affected_rows($conn); 
}

// Fungsi Edit Dokter
function edit_dokter($data_form){
    global $conn;

    // Ambil data dari tiap elemen dalam form
    $id = $data_form["id"]; 
    $nama = htmlspecialchars($data_form["nama"]);
    $alamat = htmlspecialchars($data_form["alamat"]);
    $no_hp = htmlspecialchars($data_form["no_hp"]);
    $id_poli = htmlspecialchars($data_form["nama_poli"]);

    // Query insert data
    $query = "UPDATE dokter SET nama = '$nama', alamat = '$alamat', no_hp = '$no_hp', id_poli = '$id_poli' WHERE id = $id";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}
// ==================== Fungsi Kelola Dokter End ====================

// ==================== Fungsi Kelola Pasien ====================
function generateNoRM()
{
    global $conn;
    $query = "SELECT no_rm FROM pasien ORDER BY no_rm DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // Ambil angka terakhir dan tambahkan 1
        $lastNumber = (int) substr($row['no_rm'], 3); // Misal RM-0005 -> ambil "0005" jadi integer 5
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1; // Jika belum ada data, mulai dari 1
    }

    return "RM-" . str_pad($newNumber, 4, "0", STR_PAD_LEFT); // Format RM-0001
}

// Fungsi Tambah Pasien
function tambah_pasien($data_form)
{
    global $conn;

    // Ambil data dari form
    $nama = htmlspecialchars($data_form["nama"]);
    $alamat = htmlspecialchars($data_form["alamat"]);
    $password = htmlspecialchars($data_form["password"]);
    $no_ktp = htmlspecialchars($data_form["no_ktp"]);
    $no_hp = htmlspecialchars($data_form["no_hp"]);
    $id_dokter = htmlspecialchars($data_form["id_dokter"]);
    // Generate no_rm
    $tahun_bulan = date("Ym"); // Format: YYYYMM

    // Mengambil nomor RM terakhir untuk bulan ini
    $result = mysqli_query($conn, "SELECT MAX(CAST(SUBSTRING(no_rm, 8) AS SIGNED)) AS max_no_rm FROM pasien WHERE LEFT(no_rm, 6) = '$tahun_bulan'");
    $row = mysqli_fetch_assoc($result);
    $max_no_rm = $row['max_no_rm'];

    // Membuat nomor RM baru
    $no_rm = ($max_no_rm === null) ? "$tahun_bulan-1" : "$tahun_bulan-" . ($max_no_rm + 1);

    // Query insert data
    $query = "INSERT INTO pasien (username,password,nama, alamat, no_ktp, no_hp, no_rm,id_dokter) VALUES ('$nama','$password', '$nama','$alamat', '$no_ktp', '$no_hp', '$no_rm','$id_dokter')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}


// Fungsi Delete Pasien
function hapus_pasien($id_pasien)
{
    global $conn;

    // 1. Ambil semua id_daftar_poli yang terkait dengan pasien
    $query_get_daftar_poli = "SELECT id FROM daftar_poli WHERE id_pasien = $id_pasien";
    $result_daftar_poli = mysqli_query($conn, $query_get_daftar_poli);

    while ($row_daftar_poli = mysqli_fetch_assoc($result_daftar_poli)) {
        $id_daftar_poli = $row_daftar_poli['id'];

        // 2. Ambil semua id_periksa yang terkait dengan daftar_poli
        $query_get_periksa = "SELECT id FROM periksa WHERE id_daftar_poli = $id_daftar_poli";
        $result_periksa = mysqli_query($conn, $query_get_periksa);

        while ($row_periksa = mysqli_fetch_assoc($result_periksa)) {
            $id_periksa = $row_periksa['id'];

            // 3. Hapus data di tabel detail_periksa
            $query_hapus_detail = "DELETE FROM detail_periksa WHERE id_periksa = $id_periksa";
            mysqli_query($conn, $query_hapus_detail);
        }

        // 4. Hapus data di tabel periksa
        $query_hapus_periksa = "DELETE FROM periksa WHERE id_daftar_poli = $id_daftar_poli";
        mysqli_query($conn, $query_hapus_periksa);
    }

    // 5. Hapus data di tabel daftar_poli
    $query_hapus_daftar_poli = "DELETE FROM daftar_poli WHERE id_pasien = $id_pasien";
    mysqli_query($conn, $query_hapus_daftar_poli);

    // 6. Hapus data di tabel pasien
    $query_hapus_pasien = "DELETE FROM pasien WHERE id = $id_pasien";
    mysqli_query($conn, $query_hapus_pasien);

    return mysqli_affected_rows($conn);
}




// Fungsi Edit Pasien
function edit_pasien($data_form){
    global $conn;

    // Ambil data dari tiap elemen dalam form
    $id = $data_form["id"]; 
    $nama = htmlspecialchars($data_form["nama"]);
    $alamat = htmlspecialchars($data_form["alamat"]);
    $no_ktp = htmlspecialchars($data_form["no_ktp"]);
    $no_hp = htmlspecialchars($data_form["no_hp"]);
    $no_rm = htmlspecialchars($data_form["no_rm"]);

    // Query insert data
    $query = "UPDATE pasien SET nama = '$nama', alamat = '$alamat', no_ktp = '$no_ktp', no_hp = '$no_hp', no_rm = '$no_rm' WHERE id = $id";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}
// ==================== Fungsi Kelola Pasien End ====================


// ==================== Fungsi Kelola Poli ====================

//Fungsi Create Poli
function tambah_poli($data_form){
    global $conn;

     // Ambil data dari tiap elemen dalam form
    $nama_poli = htmlspecialchars($data_form["nama_poli"]);
    $keterangan = htmlspecialchars($data_form["keterangan"]);

    // Query insert data
    $query = "INSERT INTO poli VALUES ('', '$nama_poli', '$keterangan')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// Fungsi Delete Poli
function hapus_poli($id){
    global $conn;
    mysqli_query($conn, "DELETE FROM poli WHERE id = $id");
    
    return mysqli_affected_rows($conn); 
}

// Fungsi Edit Poli
function edit_poli($data_form){
    global $conn;

    // Ambil data dari tiap elemen dalam form
    $id = $data_form["id"]; 
    $nama_poli = htmlspecialchars($data_form["nama_poli"]);
    $keterangan = htmlspecialchars($data_form["keterangan"]);

    // Query insert data
    $query = "UPDATE poli SET nama_poli = '$nama_poli', keterangan = '$keterangan' WHERE id = $id";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}
// ==================== Fungsi Kelola Poli End ====================

// ==================== Fungsi Kelola Obat ====================

// Fungsi Create Obat
function tambah_obat($data_form){
    global $conn;

     // Ambil data dari tiap elemen dalam form
    $nama_obat = htmlspecialchars($data_form["nama_obat"]);
    $kemasan = htmlspecialchars($data_form["kemasan"]);
    $harga = htmlspecialchars($data_form["harga"]);

    // Query insert data
    $query = "INSERT INTO obat VALUES ('', '$nama_obat', '$kemasan', '$harga')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// Fungsi Delete Obat
function hapus_obat($id){
    global $conn;
    mysqli_query($conn, "DELETE FROM obat WHERE id = $id");
    
    return mysqli_affected_rows($conn); 
}

// Fungsi Edit Obat
function edit_obat($data_form){
    global $conn;

    // Ambil data dari tiap elemen dalam form
    $id = $data_form["id"]; 
    $nama_obat = htmlspecialchars($data_form["nama_obat"]);
    $kemasan = htmlspecialchars($data_form["kemasan"]);
    $harga = htmlspecialchars($data_form["harga"]);

    // Query insert data
    $query = "UPDATE obat SET nama_obat = '$nama_obat', kemasan = '$kemasan', harga = '$harga' WHERE id = $id";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}
// ==================== Fungsi Kelola Obat End ====================
?>