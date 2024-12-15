<?php
session_start();

$id_daftar_poli = $_GET["id"];

if (!isset($_SESSION["login"])) {
    header("Location: ../../index.php");
    exit;
}

require '../../functions/connect_database.php';
require '../../functions/dokter_functions.php';

// Ambil data dari tabel daftar_poli dengan JOIN ke tabel pasien dan jadwal_periksa
$data_pasien = query("SELECT *
                      FROM daftar_poli
                      JOIN pasien ON daftar_poli.id_pasien = pasien.id
                      JOIN jadwal_periksa ON daftar_poli.id_jadwal = jadwal_periksa.id
                      WHERE daftar_poli.id = $id_daftar_poli;
                    ")[0];

// Ambil data dari tabel obat
$obat = query("SELECT * FROM obat");

// Cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["submit"])) {
    // Cek apakah data berhasil ditambahkan atau tidak
    if (tambah_periksa_pasien($_POST) > 0) {
        echo "
            <script>
                alert('Data berhasil ditambahkan!');
            </script>
        ";
        header("Location: memeriksa_pasien.php");
    } else {
        echo "
             <script>
                alert('Data gagal ditambahkan!');
            </script>
        ";
        header("Location: memeriksa_pasien.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Periksa Pasien</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(to right, #B3E5FC, #FFFFFF);
        }
    </style>
</head>

<body class="flex gap-5">
    <!-- Side Bar -->
    <?= include("../../components/sidebar_dokter.php"); ?>
    <!-- Side Bar End -->

    <main class="flex flex-col w-full bg-white pb-10 rounded-lg shadow-lg">
        <header class="flex items-center gap-3 px-8 py-7 shadow-lg">
            <img src="../../assets/icons/stethoscope-icon.svg" alt="" width="30px" class="invert">
            <h1 class="text-3xl font-medium">Edit Periksa Pasien</h1>
        </header>

        <article class="mx-8 mt-8 p-8 bg-white shadow-lg rounded-lg">
            <h2 class="text-2xl font-medium text-[#0277BD] mb-5">Edit Periksa Pasien</h2>
            <form action="" method="post" class="flex flex-col gap-5">
                <input type="hidden" name="id_daftar_poli" id="id_daftar_poli" value="<?= $id_daftar_poli ?>">

                <div class="flex flex-col gap-3">
                    <label for="nama" class="text-lg font-medium">Nama Pasien</label>
                    <input type="text" name="" id="nama" readonly value="<?= $data_pasien["nama"] ?>"
                        class="px-4 py-3 text-gray-400 outline-none rounded-lg border border-gray-300">
                </div>

                <div class="flex flex-col gap-3">
                    <label for="hari" class="text-lg font-medium">Hari Periksa</label>
                    <input type="text" name="hari" id="hari" value="<?= $data_pasien["hari"] ?>"
                        class="px-4 py-3 outline-none rounded-lg border border-gray-300">
                </div>

                <div class="flex flex-col gap-3">
                    <label for="catatan" class="text-lg font-medium">Catatan</label>
                    <textarea name="catatan" rows="10" cols="" class="p-3 rounded-lg border border-gray-300"></textarea>
                </div>

                <div class="flex flex-col gap-3">
                    <label for="harga" class="text-lg font-medium">Obat</label>
                    <select id="biaya_periksa" name="biaya_periksa" class="px-4 py-3 outline-none rounded-lg border border-gray-300">
                        <?php foreach ($obat as $item) : ?>
                        <option value="<?= $item["harga"] ?>"><?= $item["nama_obat"] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" name="submit"
                    class="bg-[#0288D1] w-fit mx-auto py-3 px-6 text-white font-medium rounded-lg hover:bg-[#0277BD]">Simpan Perubahan</button>
            </form>
        </article>
    </main>
</body>

</html>
