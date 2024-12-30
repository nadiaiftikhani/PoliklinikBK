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
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(to right, #A7C7E7, #E3F2FD);
            color: #1A1A1A;
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .card img {
            width: 80px;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body class="flex gap-5">
    <!-- Side Bar -->
    <?= include("../../components/sidebar_admin.php"); ?>
    <!-- Side Bar End -->

    <main class="grid grid-cols-2 gap-10 w-full p-10">
        <!-- Card 1: Kelola Dokter -->
        <a href="kelola_dokter.php"
            class="card flex flex-col justify-center items-center bg-white p-10 rounded-lg shadow-lg hover:bg-[#A7C7E7] hover:text-white">
            <img src="../../assets/icons/doctor-icon.svg" alt="Dokter">
            <h3 class="text-xl font-semibold">Manajemen Dokter</h3>
        </a>

        <!-- Card 2: Kelola Pasien -->
        <a href="kelola_pasien.php"
            class="card flex flex-col justify-center items-center bg-white p-10 rounded-lg shadow-lg hover:bg-[#A7C7E7] hover:text-white">
            <img src="../../assets/icons/patient-icon.svg" alt="Pasien">
            <h3 class="text-xl font-semibold">Manajemen Pasien</h3>
        </a>

        <!-- Card 3: Kelola Poli -->
        <a href="kelola_poli.php"
            class="card flex flex-col justify-center items-center bg-white p-10 rounded-lg shadow-lg hover:bg-[#A7C7E7] hover:text-white">
            <img src="../../assets/icons/clinic-icon.svg" alt="Poli">
            <h3 class="text-xl font-semibold">Manajemen Poli</h3>
        </a>

        <!-- Card 4: Kelola Obat -->
        <a href="kelola_obat.php"
            class="card flex flex-col justify-center items-center bg-white p-10 rounded-lg shadow-lg hover:bg-[#A7C7E7] hover:text-white">
            <img src="../../assets/icons/medicine-icon.svg" alt="Obat">
            <h3 class="text-xl font-semibold">Manajemen Obat</h3>
        </a>
    </main>
</body>

</html>
