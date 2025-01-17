<?php
session_start();
require '../../functions/connect_database.php';
require '../../functions/dokter_functions.php';

if (!isset($_SESSION["login"])) {
    header("Location: ../../index.php");
    exit;
}

$id_daftar_poli = $_GET["id"];

// Ambil data dari tabel daftar_poli dengan JOIN ke tabel pasien dan jadwal_periksa
$query = "SELECT * 
          FROM daftar_poli 
          JOIN pasien ON daftar_poli.id_pasien = pasien.id 
          JOIN jadwal_periksa ON daftar_poli.id_jadwal = jadwal_periksa.id 
          WHERE daftar_poli.id = $id_daftar_poli";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}
$data_pasien = mysqli_fetch_assoc($result);

// Ambil data dari tabel obat
$query_obat = "SELECT * FROM obat";
$result_obat = mysqli_query($conn, $query_obat);
if (!$result_obat) {
    die("Query Error: " . mysqli_error($conn));
}
$obat = mysqli_fetch_all($result_obat, MYSQLI_ASSOC);

// Ambil data dari tabel periksa
$query_periksa = "SELECT * FROM periksa WHERE id_daftar_poli = $id_daftar_poli ORDER BY id DESC";
$result_periksa = mysqli_query($conn, $query_periksa);
if (!$result_periksa) {
    die("Query Error: " . mysqli_error($conn));
}
$periksa = mysqli_fetch_assoc($result_periksa);
$id_periksa = $periksa['id'] ?? null;

// Ambil data dari detail_periksa
$id_selected_obat = [];
if ($id_periksa) {
    $query_detail_periksa = "SELECT * FROM detail_periksa WHERE id_periksa = $id_periksa";
    $result_detail_periksa = mysqli_query($conn, $query_detail_periksa);
    if (!$result_detail_periksa) {
        die("Query Error: " . mysqli_error($conn));
    }
    while ($row = mysqli_fetch_assoc($result_detail_periksa)) {
        array_push($id_selected_obat, $row['id_obat']);
    }
}

// Cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["submit"])) {
    if (tambah_periksa_pasien($_POST) > 0) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location.href = 'memeriksa_pasien.php';</script>";
    } else {
        echo "<script>alert('Data gagal ditambahkan!'); window.location.href = 'memeriksa_pasien.php';</script>";
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
            <h1 class="text-3xl font-medium">123Edit Periksa Pasien</h1>
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
                    <textarea name="catatan" rows="10" cols="" class="p-3 rounded-lg border border-gray-300"><?= isset($periksa['catatan']) ? $periksa['catatan'] : '' ?></textarea>
                </div>

                <div class="flex flex-col gap-3">
                    <label for="harga" class="text-lg font-medium">Obat</label>
                    <select id="id_obat" name="id_obat" class="px-4 py-3 outline-none rounded-lg border border-gray-300">
                        <?php foreach ($obat as $item) : ?>
                            <option value="<?= $item["id"] ?>"><?= $item["nama_obat"] ?> - <?= $item["harga"] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" id="buttonAddObat" class="btn btn-outline-primary btn-primary" style="width: 150px"><i class="mdi mdi-plus me-2"></i>Tambah</button>
                </div>

                <div class="flex flex-col gap-3">
                    <label for="biaya_periksa" class="text-lg font-medium">Total Harga</label>
                    <input type="text" name="biaya_periksa_mock" id="biaya_periksa_mock" value="Rp. 150.000" disabled class="px-4 py-3 outline-none rounded-lg border border-gray-300">
                    <input type="hidden" name="biaya_periksa" id="biaya_periksa" value="150000" readonly class="px-4 py-3 outline-none rounded-lg border border-gray-300">
                    <input type="hidden" name="id_obat_selected" value="[<?= implode(',', $id_selected_obat) ?>]">

                </div>

                <div class="flex flex-col gap-3">
                    <div id="info-obat" class="col-sm-10 mt-3">
                    </div>
                </div>

                <button type="submit" name="submit"
                    class="bg-[#0288D1] w-fit mx-auto py-3 px-6 text-white font-medium rounded-lg hover:bg-[#0277BD]">Simpan Perubahan</button>
            </form>
        </article>
    </main>
</body>

</html>

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

<script>
    var data_obat = <?= json_encode($obat) ?>;

    function formatRupiah(angka) {
        var number_string = angka.toString(),
            sisa = number_string.length % 3,
            rupiah = number_string.substr(0, sisa),
            ribuan = number_string.substr(sisa).match(/\d{3}/g);

        if (ribuan) {
            var separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return 'Rp ' + rupiah;
    }

    const renderInfoObat = () => {
        let id_obat_selected = $('input[name="id_obat_selected"]').val();

        // Parse the existing value to an array
        id_obat_selected = id_obat_selected ? JSON.parse(id_obat_selected) : [];

        const renderHtml = id_obat_selected.map(id => {
            const obat = data_obat.find(o => o.id == id);
            if (!obat) return '';
            return `<p>${obat.nama_obat} - ${formatRupiah(obat.harga)}</p>`;
        });

        const biaya_periksa = 150000;
        const total = id_obat_selected.reduce((acc, curr) => {
            const obat = data_obat.find(o => o.id == curr);
            if (!obat) return acc;
            return acc + parseInt(obat.harga);
        }, 0);

        $('input[name="biaya_periksa_mock"]').val(formatRupiah(total + biaya_periksa));
        $('input[name="biaya_periksa"]').val(total + biaya_periksa);
        $('#info-obat').html(renderHtml);
    }
    const addObat = () => {
        const id_obat = $('#id_obat').val();
        let id_obat_selected = $('input[name="id_obat_selected"]').val();

        // Parse the existing value to an array
        id_obat_selected = id_obat_selected ? JSON.parse(id_obat_selected) : [];

        // Check if id_obat already exists in the array
        if (!id_obat_selected.includes(id_obat)) {
            // Push the new id_obat to the array
            id_obat_selected.push(id_obat);

            // Update the input value with the new array
            $('input[name="id_obat_selected"]').val(JSON.stringify(id_obat_selected));
        } else {
            alert('Obat sudah ditambahkan.');
        }

        renderInfoObat();
    }

    jQuery(document).ready(function() {

        // getObat();
        renderInfoObat();

        $('#buttonAddObat').on('click', function() {
            addObat();
        });
    });
</script>