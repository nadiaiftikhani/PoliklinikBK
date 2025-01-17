<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../../index.php");
    exit;
}

require '../../functions/connect_database.php';
require '../../functions/dokter_functions.php';

// Ambil data dari tabel daftar_poli dengan JOIN ke tabel pasien
$data_periksa = query("SELECT *
                      FROM daftar_poli
                      JOIN pasien ON daftar_poli.id_pasien = pasien.id
                      JOIN jadwal_periksa ON daftar_poli.id_jadwal = jadwal_periksa.id
                      WHERE jadwal_periksa.id_dokter = " . $_SESSION["id"] . "
                    ");

// Cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["submit"])) {
    // Cek apakah data berhasil ditambahkan atau tidak
    if (tambah($_POST) > 0) {
        echo "
            <script>
                alert('Data berhasil ditambahkan!');
            </script>
        ";
        header("Location: jadwal_periksa.php");
    } else {
        echo "
             <script>
                alert('Data gagal ditambahkan!');
            </script>
        ";
        header("Location: jadwal_periksa.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pasien</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(to right, #D0E7FF, #FFFFFF);
        }
    </style>
</head>

<body class="flex gap-5">
    <!-- Side Bar -->
    <?= include("../../components/sidebar_dokter.php"); ?>
    <!-- Side Bar End -->

    <main class="flex flex-col w-full bg-white pb-10 rounded-lg shadow-lg">
        <header class="flex items-center gap-3 px-8 py-7 shadow-lg">
            <img src="../../assets/icons/notebook-pen.svg" alt="" width="30px" class="invert">
            <h1 class="text-3xl font-medium">Riwayat Pasien</h1>
        </header>

        <article class="mx-8 mt-8 p-8 bg-white shadow-lg rounded-lg">
            <h2 class="text-2xl font-medium text-[#1E88E5] mb-5">Daftar Riwayat Pasien</h2>
            <table class="w-full table-auto border border-gray-300">
                <thead class="bg-[#90CAF9] text-white">
                    <tr>
                        <th class="w-[5%] py-3">No</th>
                        <th class="w-[20%] py-3">Nama Pasien</th>
                        <th class="w-[25%] py-3">Alamat</th>
                        <th class="w-[15%] py-3">No KTP</th>
                        <th class="w-[15%] py-3">No Telepon</th>
                        <th class="w-[10%] py-3">No RM</th>
                        <th class="w-[10%] py-3">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-white">
                    <?php $index = 1; ?>
                    <?php foreach ($data_periksa as $item) : ?>
                    <tr class="border-t">
                        <td class="py-5 text-center">
                            <?= $index ?>
                        </td>

                        <td class="py-5 text-center">
                            <?= $item["nama"] ?>
                        </td>

                        <td class="py-5 text-center">
                            <?= $item["alamat"] ?>
                        </td>

                        <td class="py-5 text-center">
                            <?= $item["no_ktp"] ?>
                        </td>

                        <td class="py-5 text-center">
                            <?= $item["no_hp"] ?>
                        </td>

                        <td class="py-5 text-center">
                            <?= $item["no_rm"] ?>
                        </td>

                        <td class="py-5 text-center">
                            <a href="detail_periksa.php?id=<?= $item["id"] ?>" class="bg-[#42A5F5] px-6 py-2 rounded-lg text-white hover:bg-[#1E88E5]">Detail</a>
                        </td>
                    </tr>
                    <?php $index++ ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </article>
    </main>
</body>

</html>
