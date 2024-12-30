<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../../index.php");
    exit;
}

require '../../functions/connect_database.php';
require '../../functions/admin_functions.php';

// Ambil data dari tabel obat
$obat = query("SELECT * FROM obat");

// Cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["submit"])) {
    // Cek apakah data berhasil ditambahkan atau tidak
    if (tambah_obat($_POST) > 0) {
        echo "
            <script>
                alert('Data berhasil ditambahkan!');
            </script>
        ";
        header("Location: kelola_obat.php");
    } else {
        echo "
             <script>
                alert('Data gagal ditambahkan!');
            </script>
        ";
        header("Location: kelola_obat.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Obat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Baby Blue Gradient */
        .gradient-bg {
            background: linear-gradient(135deg, #A0D8FF, #66C2FF);
        }

        /* Custom font */
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="flex gap-5 gradient-bg min-h-screen">
    <!-- Side Bar -->
    <?= include("../../components/sidebar_admin.php"); ?>
    <!-- Side Bar End -->

    <main class="flex flex-col w-full bg-white pb-10 px-8">
        <header class="flex items-center gap-3 py-6 mb-6 shadow-lg rounded-xl bg-[#004079] text-white">
            <img src="../../assets/icons/pill-icon.svg" alt="" width="30px" class="invert">
            <h1 class="text-3xl font-medium">Manajemen Obat</h1>
        </header>

        <article>
            <!-- Form Section -->
            <form action="" method="post" class="flex flex-col gap-5 mt-8 p-8 bg-white rounded-2xl shadow-md">
                <div class="flex flex-col gap-3">
                    <label for="nama_obat" class="text-lg font-medium text-[#004079]">Nama Obat</label>
                    <input type="text" name="nama_obat" id="nama_obat" placeholder="Nama Obat"
                        class="px-4 py-3 outline-none rounded-lg border border-[#004079]">
                </div>

                <div class="flex flex-col gap-3">
                    <label for="kemasan" class="text-lg font-medium text-[#004079]">Kemasan</label>
                    <input type="text" name="kemasan" id="kemasan" placeholder="Kemasan"
                        class="px-4 py-3 outline-none rounded-lg border border-[#004079]">
                </div>

                <div class="flex flex-col gap-3">
                    <label for="harga" class="text-lg font-medium text-[#004079]">Harga</label>
                    <input type="number" name="harga" id="harga" placeholder="Harga"
                        class="px-4 py-3 outline-none rounded-lg border border-[#004079]">
                </div>

                <button type="submit" name="submit"
                    class="bg-[#004079] w-fit py-3 px-6 text-white font-medium rounded-lg hover:bg-[#003057] transition-all">
                    Tambah Data
                </button>
            </form>

            <!-- Daftar Obat Section -->
            <section class="mt-8 p-8 bg-white rounded-2xl shadow-md">
                <h1 class="mb-5 text-2xl font-medium text-[#004079]">Daftar Obat</h1>
                <table class="w-full table-fixed border border-yellow-500">
                    <thead class="bg-[#004079] text-white">
                        <tr>
                            <th class="w-[5%] border py-3">No</th>
                            <th class="w-[20%] border py-3">Nama Obat</th>
                            <th class="w-[25%] border py-3">Kemasan</th>
                            <th class="w-[20%] border py-3">Harga</th>
                            <th class="w-[30%] border py-3">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $index = 1; ?>
                        <?php foreach ($obat as $item) : ?>
                        <tr>
                            <td class="border py-5 text-center"><?= $index ?></td>
                            <td class="border py-5 text-center"><?= $item["nama_obat"] ?></td>
                            <td class="border py-5 text-center"><?= $item["kemasan"] ?></td>
                            <td class="border py-5 text-center">Rp <?= number_format($item["harga"], 0, ',', '.') ?></td>
                            <td class="border py-5 text-center">
                                <a href="edit_obat.php?id=<?= $item["id"] ?>"
                                    class="bg-green-500 px-6 py-2 rounded-lg text-white mr-3 hover:bg-green-600 transition-all">Edit</a>
                                <a href="hapus_obat.php?id=<?= $item["id"] ?>"
                                    class="bg-red-500 px-6 py-2 rounded-lg text-white hover:bg-red-600 transition-all">Delete</a>
                            </td>
                        </tr>
                        <?php $index++ ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </article>
    </main>
</body>

</html>
