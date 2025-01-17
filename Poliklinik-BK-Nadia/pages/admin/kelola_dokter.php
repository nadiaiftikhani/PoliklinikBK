<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../../index.php");
    exit;
}

require '../../functions/connect_database.php';
require '../../functions/admin_functions.php';

// Ambil data dari tabel dokter dengan JOIN ke tabel poli
$query = "SELECT dokter.*, poli.nama_poli
          FROM dokter
          JOIN poli ON dokter.id_poli = poli.id";

$dokter = mysqli_query($conn, $query);

// Ambil data dari tabel poli
$poli = query("SELECT * FROM poli");

// Cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["submit"])) {
    // Cek apakah data berhasil ditambahkan atau tidak
    if (tambah_dokter($_POST) > 0) {
        echo "
            <script>
                alert('Data berhasil ditambahkan!');
                window.location.href = 'kelola_dokter.php';
            </script>
        ";
        exit;
    } else {
        echo "
            <script>
                alert('Data gagal ditambahkan!');
                window.location.href = 'kelola_dokter.php';
            </script>
        ";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Dokter</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(to right, #A7C7E7, #E3F2FD);
        }
    </style>
</head>

<body class="flex gap-5">
    <!-- Side Bar -->
    <?= include("../../components/sidebar_admin.php"); ?>
    <!-- Side Bar End -->

    <main class="flex flex-col w-full bg-white pb-10 rounded-lg shadow-lg">
        <!-- Header -->
        <header class="flex items-center gap-3 px-8 py-7 bg-[#A7C7E7] text-white rounded-t-lg">
            <img src="../../assets/icons/doctor-icon.svg" alt="Doctor Icon" width="30px">
            <h1 class="text-3xl font-medium">Manajemen Dokter</h1>
        </header>

        <!-- Form Tambah Dokter -->
        <article class="px-8 py-5">
            <form action="" method="post" class="flex flex-col gap-5 bg-white p-8 shadow-lg rounded-lg">
                <h2 class="text-xl font-semibold text-[#1A73E8]">Tambah Data Dokter</h2>
                
                <div class="flex flex-col gap-3">
                    <label for="nama" class="text-lg font-medium">Nama Dokter</label>
                    <input type="text" name="nama" id="nama" placeholder="Nama Dokter" class="px-4 py-3 outline-none border rounded-lg">
                </div>

                <div class="flex flex-col gap-3">
                    <label for="alamat" class="text-lg font-medium">Alamat</label>
                    <input type="text" name="alamat" id="alamat" placeholder="Alamat" class="px-4 py-3 outline-none border rounded-lg">
                </div>

                <div class="flex flex-col gap-3">
                    <label for="no_hp" class="text-lg font-medium">Nomor Telepon</label>
                    <input type="number" name="no_hp" id="no_hp" placeholder="Nomor Telepon" class="px-4 py-3 outline-none border rounded-lg">
                </div>

                <div class="flex flex-col gap-3">
                    <label for="nama_poli" class="text-lg font-medium">Poli</label>
                    <select id="nama_poli" name="nama_poli" class="px-4 py-3 outline-none border rounded-lg">
                        <?php foreach ($poli as $item) : ?>
                            <option value="<?= $item["id"] ?>"><?= $item["nama_poli"] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" name="submit" class="bg-[#1A73E8] py-3 px-6 text-white font-medium rounded-lg hover:bg-[#166ABF]">
                    Tambah Data
                </button>
            </form>

            <!-- Tabel Daftar Dokter -->
            <section class="mt-8 p-8 bg-white shadow-lg rounded-lg">
                <h2 class="mb-5 text-2xl font-medium text-[#1A73E8]">Daftar Dokter</h2>
                <table class="w-full table-auto border border-gray-300">
                    <thead class="bg-[#A7C7E7] text-white">
                        <tr>
                            <th class="w-[5%] py-3">No</th>
                            <th class="w-[20%] py-3">Nama</th>
                            <th class="w-[25%] py-3">Alamat</th>
                            <th class="w-[20%] py-3">No. HP</th>
                            <th class="w-[20%] py-3">Poli</th>
                            <th class="w-[30%] py-3">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        <?php $index = 1; ?>
                        <?php while ($item = mysqli_fetch_assoc($dokter)) : ?>
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
                                    <?= $item["no_hp"] ?>
                                </td>
                                <td class="py-5 text-center">
                                    <?= $item["nama_poli"] ?>
                                </td>
                                <td class="p-5 flex text-center">
                                    <a href="edit_dokter.php?id=<?= $item["id"] ?>" class="bg-green-500 px-6 py-2 rounded-lg text-white mr-3 hover:bg-green-600">
                                        Edit
                                    </a>
                                    <a href="hapus_dokter.php?id=<?= $item["id"] ?>" class="bg-red-500 px-6 py-2 rounded-lg text-white hover:bg-red-600">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                            <?php $index++ ?>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
        </article>
    </main>
</body>

</html>
