<?php
session_start();

$username = $_SESSION['username'];

if(!isset($_SESSION["login"])){
    header("Location: ../../index.php");
    exit;
}

require '../../functions/connect_database.php';
require '../../functions/admin_functions.php';

// Ambil data dari tabel poli
$poli = query("SELECT * FROM poli");

// Langkah 2: Menggunakan Query SQL untuk Mengambil id_dokter
$query = "SELECT no_rm FROM pasien WHERE username = '$username'";
$result = mysqli_query($conn, $query);

// Langkah 3: Eksekusi Query dan Mendapatkan Hasil
if ($row = mysqli_fetch_assoc($result)) {
    $no_rm = $row['no_rm'];
}

if(isset($_POST["poli"])){
    echo "
        <script>
            alert('Hai!');
        </script>
    ";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pasien</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(to right, #E3F2FD, #FFFFFF);
        }
    </style>
</head>

<body class="flex gap-5 bg-[#E3F2FD]">
    <!-- Side Bar -->
    <?= include("../../components/sidebar_pasien.php"); ?>
    <!-- Side Bar End -->

    <main class="flex flex-col w-full bg-white pb-10 rounded-lg shadow-lg">
         <header class="flex items-center gap-3 px-8 py-7 bg-[#BBDEFB] rounded-t-lg shadow-lg">
            <img src="../../assets/icons/notebook-pen.svg" alt="" width="30px" class="invert">
            <h1 class="text-3xl font-medium text-[#1565C0]">Riwayat Daftar Poli</h1>
        </header>

        <article class="mx-8 mt-8 p-8 bg-white shadow-lg rounded-lg">
            <div class="flex flex-col gap-3">
                <h1 class="bg-[#E3F2FD] px-5 py-4 text-[#0D47A1] text-xl font-medium rounded-t-2xl">Riwayat Daftar Poli</h1>
                <table class="w-full table-fixed border border-blue-500">
                       <thead>
                           <tr>
                               <th class="border border-slate-500 py-3">No</th>
                               <th class="border border-slate-500 py-3">id Poli</th>
                               <th class="border border-slate-500 py-3">id obat</th>
                           </tr>
                       </thead>
                   </table>
            </div>
        </article>
    </main>
</body>

</html>
