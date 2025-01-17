<?php
session_start();

if(!isset($_SESSION["login"])){
    header("Location: ../../index.php");
    exit;
}

require '../../functions/connect_database.php';
require '../../functions/dokter_functions.php';

// Ambil data dari tabel daftar_poli dengan JOIN ke tabel pasien
$query = "SELECT daftar_poli.*, pasien.nama, daftar_poli.id as id_periksa
          FROM daftar_poli
          JOIN jadwal_periksa ON daftar_poli.id_jadwal = jadwal_periksa.id
          JOIN pasien ON daftar_poli.id_pasien = pasien.id
          WHERE jadwal_periksa.id_dokter = " . $_SESSION["id"];

$daftar_poli = mysqli_query($conn, $query);

// Cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["submit"])){
    // Cek apakah data berhasil ditambahkan atau tidak
    if(tambah($_POST) > 0){
        echo "
            <script>
                alert('Data berhasil ditambahkan!');
            </script>
        ";
        header("Location: jadwal_periksa.php");
    } else{
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
    <title>Memeriksa Pasien</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(to right, #B3E5FC, #FFFFFF);
        }

        main {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table thead {
            background: linear-gradient(to right, #81D4FA, #BBDEFB);
        }

        table tbody tr:nth-child(even) {
            background: #F1F8E9;
        }

        a {
            transition: background-color 0.3s;
        }

        a.bg-[#42A5F5]:hover {
            background-color: #1E88E5;
        }

        a.bg-[#43A047]:hover {
            background-color: #388E3C;
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
            <h1 class="text-3xl font-medium">Memeriksa Pasien</h1>
        </header>

        <article class="mx-8 mt-8 p-8 bg-white shadow-lg rounded-lg">
            <h2 class="text-2xl font-medium text-[#0288D1] mb-5">Daftar Pasien</h2>
            <table class="w-full table-auto border border-gray-300">
                <thead>
                    <tr>
                        <th class="w-[5%] py-3">No Urut</th>
                        <th class="w-[20%] py-3">Nama Pasien</th>
                        <th class="w-[25%] py-3">Keluhan</th>
                        <th class="w-[30%] py-3">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $index = 1; ?>
                    <?php foreach($daftar_poli as $item) : ?>
                    <tr class="border-t">
                        <td class="py-5 text-center">
                            <?= $index  ?>
                        </td>

                        <td class="py-5 text-center">
                            <?= $item["nama"] ?>
                        </td>

                        <td class="py-5 text-center">
                            <?= $item["keluhan"] ?>
                        </td>

                        <td class="py-5 text-center flex justify-center gap-3">
                            <?php 
                            if ($item["status_periksa"] == "Menunggu") {
                                echo '<a href="edit_periksa_pasien.php?id=' . $item["id"] . '" class="bg-[#42A5F5] px-4 py-2 rounded-lg text-white">Periksa</a>';
                            } else {
                                echo '<a href="edit_periksa_pasien.php?id=' . $item["id"] . '" class="bg-[#43A047] px-4 py-2 rounded-lg text-white">Edit</a>';
                            }
                            ?>
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
