<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../../index.php");
    exit;
}

require '../../functions/connect_database.php';
require '../../functions/dokter_functions.php';

// Ambil data dari tabel detail_periksa
$data_detail = query("SELECT *
                      FROM detail_periksa
                      JOIN periksa ON detail_periksa.id_periksa = periksa.id
                      JOIN obat ON detail_periksa.id_obat = obat.id
                    ");

// Cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["submit"])) {
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
    <title>Detail Periksa Pasien</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(to right, #B3E5FC, #E3F2FD);
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
            <h1 class="text-3xl font-medium">Detail Periksa Pasien</h1>
        </header>

        <article class="mx-8 mt-8 p-8 bg-white shadow-lg rounded-lg">
            <h2 class="text-2xl font-medium text-[#0277BD] mb-5">Detail Periksa</h2>
            <table class="w-full table-auto border border-gray-300">
                <thead class="bg-[#81D4FA] text-white">
                    <tr>
                        <th class="w-[5%] py-3">No</th>
                        <th class="w-[15%] py-3">Hari Periksa</th>
                        <th class="w-[15%] py-3">Nama Pasien</th>
                        <th class="w-[15%] py-3">Nama Dokter</th>
                        <th class="w-[20%] py-3">Keluhan</th>
                        <th class="w-[15%] py-3">Catatan</th>
                        <th class="w-[10%] py-3">Obat</th>
                        <th class="w-[10%] py-3">Biaya Periksa</th>
                    </tr>
                </thead>

                <tbody class="bg-white">
                    <?php $index = 1; ?>
                    <?php foreach ($data_detail as $item) : ?>
                    <tr class="border-t">
                        <td class="py-5 text-center"><?= $index ?></td>
                        <td class="py-5 text-center"><?= $item["hari"] ?></td>
                        <td class="py-5 text-center"><?= $item["nama_pasien"] ?></td>
                        <td class="py-5 text-center"><?= $item["nama_dokter"] ?></td>
                        <td class="py-5 text-center"><?= $item["keluhan"] ?></td>
                        <td class="py-5 text-center"><?= $item["catatan"] ?></td>
                        <td class="py-5 text-center"><?= $item["nama_obat"] ?></td>
                        <td class="py-5 text-center"><?= $item["biaya_periksa"] ?></td>
                    </tr>
                    <?php $index++ ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </article>
    </main>
</body>

</html>
