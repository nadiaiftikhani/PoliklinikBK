<?php 
// ==================== Fungsi Jadwal Periksa ====================

// Fungsi Create Jadwal Periksa
function tambah_jadwal($data_form){
    global $conn;

     // Ambil data dari tiap elemen dalam form
    $id_dokter = htmlspecialchars($data_form["id_dokter"]);
    $hari = htmlspecialchars($data_form["hari"]);
    $jam_mulai = htmlspecialchars($data_form["jam_mulai"]);
    $jam_selesai = htmlspecialchars($data_form["jam_selesai"]);

    // Query insert data
    $query = "INSERT INTO jadwal_periksa VALUES ('', '$id_dokter', '$hari', '$jam_mulai', '$jam_selesai')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// Fungsi Delete Jadwal Periksa
function hapus_jadwal($id){
    global $conn;
    mysqli_query($conn, "DELETE FROM jadwal_periksa WHERE id = $id");
    
    return mysqli_affected_rows($conn); 
}

// Fungsi Edit Jadwal Periksa
function edit($id, $hari, $jam_mulai, $jam_selesai)
{
    global $conn;

    $id = intval($id);
    $hari = mysqli_real_escape_string($conn, htmlspecialchars($hari));
    $jam_mulai = mysqli_real_escape_string($conn, htmlspecialchars($jam_mulai));
    $jam_selesai = mysqli_real_escape_string($conn, htmlspecialchars($jam_selesai));

    $query = "
        UPDATE jadwal_periksa 
        SET hari = '$hari', jam_mulai = '$jam_mulai', jam_selesai = '$jam_selesai' 
        WHERE id = $id
    ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}


// ==================== Fungsi Jadwal Periksa End ====================

// Fungsi Create Periksa Pasien
function tambah_periksa_pasien($data_form){
    global $conn;

    // echo "asdasd";
    // var_dump($data_form);
    
    // die();

     // Ambil data dari tiap elemen dalam form
    $id_daftar_poli = htmlspecialchars($data_form["id_daftar_poli"]);
    $hari = htmlspecialchars($data_form["hari"]);
    $catatan = htmlspecialchars($data_form["catatan"]);
    $biaya_periksa = htmlspecialchars($data_form["biaya_periksa"]) + 150000;

    // Query insert data
    $query1 = "INSERT INTO periksa(id_daftar_poli,tgl_periksa,catatan,biaya_periksa) VALUES ('$id_daftar_poli', '$hari', '$catatan', '$biaya_periksa')";
    $periksa = mysqli_query($conn, $query1);
    $id_periksa = mysqli_insert_id($conn);

    $query2 = "UPDATE daftar_poli SET status_periksa = 'Selesai'";
    mysqli_query($conn, $query2);

    // var_dump($periksa);
    // echo $id_periksa;
    // die();
    foreach (json_decode($data_form['id_obat_selected']) as $key => $value) {
        $query1 = "INSERT INTO `detail_periksa` (`id`, `id_periksa`, `id_obat`) VALUES (NULL, '$id_periksa', '$value')";
        mysqli_query($conn, $query1);
    }

    return mysqli_affected_rows($conn);
}

// ==================== Fungsi Profil ====================

// Fungsi Edit Dokter
function edit_profil($data_form){
    global $conn;

    // Ambil data dari tiap elemen dalam form
    $username = $data_form["username"]; 
    $nama = htmlspecialchars($data_form["nama"]);
    $alamat = htmlspecialchars($data_form["alamat"]);
    $no_hp = htmlspecialchars($data_form["no_hp"]);

    // Query insert data
    $query = "UPDATE dokter SET nama = '$nama', alamat = '$alamat', no_hp = '$no_hp' WHERE username = '$username'";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}
// ==================== Fungsi Profil End ====================
?>