<?php
session_start();

if(!isset($_SESSION["login"])){
    header("Location: ../../index.php");
    exit;
}

require '../../functions/connect_database.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dokter</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #B3E5FC, #FFFFFF);
        }

        a {
            background: linear-gradient(135deg, #81D4FA, #E3F2FD);
            border-radius: 10px;
            color: white;
        }

        a:hover {
            background: linear-gradient(135deg, #4FC3F7, #BBDEFB);
            color: white;
        }

        main {
            background: white;
            border-radius: 15px;
        }

        body, a, main {
            color: #333333; /* Dark font color for better visibility */
        }
    </style>
</head>

<body class="flex gap-5">
    <!-- Side Bar -->
    <?= include("../../components/sidebar_dokter.php"); ?>
    <!-- Side Bar End -->

    <main class="grid grid-cols-2 divide-x divide-y divide-gray-300 overflow-hidden w-full p-5 my-7 mr-5">
        <a href="jadwal_periksa.php"
            class="flex justify-center items-center gap-5 px-14 py-10 group transition-all duration-300">
            <img src="../../assets/icons/clipboard-list.svg" alt="" width="100px"
                class="p-2 rounded-lg invert group-hover:invert-0">
        </a>

        <a href="memeriksa_pasien.php"
            class="flex justify-center items-center gap-5 px-14 py-10 group transition-all duration-300">
            <img src="../../assets/icons/stethoscope-icon.svg" alt="" width="100px"
                class="p-2 rounded-lg invert group-hover:invert-0">
        </a>

        <a href="riwayat_pasien.php"
            class="flex justify-center items-center gap-5 px-14 py-10 group transition-all duration-300">
            <img src="../../assets/icons/notebook-pen.svg" alt="" width="100px"
                class="p-2 rounded-lg invert group-hover:invert-0">
        </a>

        <a href="profil.php"
            class="flex justify-center items-center gap-5 px-14 py-10 group transition-all duration-300">
            <img src="../../assets/icons/pasien-icon.svg" alt="" width="100px"
                class="p-2 rounded-lg invert group-hover:invert-0">
        </a>
    </main>
</body>

</html>
