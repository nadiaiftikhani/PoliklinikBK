<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../../index.php");
    exit;
}

require '../../functions/connect_database.php';
require '../../functions/admin_functions.php';

// Ambil data dari tabel poli
$pasien = query("SELECT * FROM pasien");

// Fungsi untuk mendapatkan nomor RM terakhir
function getLatestRMNumber()
{
    global $conn;

    $query = "SELECT no_rm FROM pasien ORDER BY no_rm DESC LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        return $row['no_rm'];
    }

    return null;
}

// Fungsi untuk menghasilkan nomor RM baru
function generateNewRMNumber($latestRM)
{
    // Pisahkan tahun dan nomor urut
    list($tahun, $nomorUrut) = explode('-', $latestRM);

    // Tambah 1 ke nomor urut
    $newNomorUrut = intval($nomorUrut) + 1;

    // Gabungkan tahun dan nomor urut yang baru
    $newRMNumber = $tahun . '-' . $newNomorUrut;

    return $newRMNumber;
}

// Mendapatkan nomor RM terakhir
$latestRM = getLatestRMNumber();

// Jika tidak ada nomor RM sebelumnya, gunakan nomor RM awal
if (!$latestRM) {
    $latestRM = '202312-1';
}

// Menghasilkan nomor RM baru
$newRMNumber = generateNewRMNumber($latestRM);

// Cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["submit"])) {
    // Cek apakah data berhasil ditambahkan atau tidak
    if (tambah_pasien($_POST) > 0) {
        echo "
            <script>
                alert('Data berhasil ditambahkan!');
            </script>
        ";
        header("Location: kelola_pasien.php");
    } else {
        echo "
             <script>
                alert('Data gagal ditambahkan!');
            </script>
        ";
        header("Location: kelola_pasien.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pasien</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(to right, #A2C8FF, #89CFF0);
            font-family: 'Arial', sans-serif;
        }

        .container {
            display: flex;
            gap: 2rem;
        }

        .main-content {
            width: 100%;
            padding: 2rem;
            background-color: #fff;
            border-radius: 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            background-color: #f3f4f6;
            padding: 2rem;
            border-radius: 1rem;
        }

        .form-container input,
        .form-container button {
            padding: 0.8rem;
            border-radius: 0.5rem;
            border: 1px solid #ccc;
        }

        .form-container button {
            background-color: #004079;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container button:hover {
            background-color: #002f5b;
        }

        .table-container {
            margin-top: 2rem;
            padding: 2rem;
            border-radius: 1rem;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        th,
        td {
            padding: 1rem;
            border: 1px solid #ddd;
        }

        th {
            background-color: #004079;
            color: white;
        }

        .actions a {
            margin: 0.5rem;
            padding: 0.5rem 1rem;
            text-decoration: none;
            color: white;
            border-radius: 0.5rem;
        }

        .actions .edit {
            background-color: #28a745;
        }

        .actions .delete {
            background-color: #dc3545;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Side Bar -->
        <?= include("../../components/sidebar_admin.php"); ?>
        <!-- End of Side Bar -->

        <main class="main-content">
            <header class="flex items-center gap-3 px-8 py-7 shadow-lg">
                <img src="../../assets/icons/pasien-icon.svg" alt="" width="30px" class="invert">
                <h1 class="text-3xl font-medium">Manajemen Pasien</h1>
            </header>

            <article>
                <form action="" method="post" class="form-container">
                    <div class="flex flex-col gap-3">
                        <label for="nama" class="text-lg font-medium">Nama Pasien</label>
                        <input type="text" name="nama" id="nama" placeholder="Nama Pasien">
                    </div>
                    <div class="flex flex-col gap-3">
                        <label for="nama" class="text-lg font-medium">Password</label>
                        <input type="text" name="password" id="nama" placeholder="Password">
                    </div>

                    <div class="flex flex-col gap-3">
                        <label for="alamat" class="text-lg font-medium">Alamat</label>
                        <input type="text" name="alamat" id="alamat" placeholder="Alamat">
                    </div>

                    <div class="flex flex-col gap-3">
                        <label for="no_ktp" class="text-lg font-medium">Nomor KTP</label>
                        <input type="text" name="no_ktp" id="no_ktp" placeholder="Nomor KTP">
                    </div>

                    <div class="flex flex-col gap-3">
                        <label for="no_hp" class="text-lg font-medium">Nomor Telepon</label>
                        <input type="text" name="no_hp" id="no_hp" placeholder="Nomor Telepon">
                    </div>

                    <div class="flex flex-col gap-3">
                        <label for="no_rm" class="text-lg font-medium">Nomor RM</label>
                        <input type="text" name="no_rm" value="<?php echo $newRMNumber; ?>" id="no_rm" readonly placeholder="Nomor RM">
                    </div>
                    <select id="id_dokter" name="id_dokter" class="bg-blue-50 px-5 py-3 outline-none rounded-lg border-2 border-blue-300 focus:ring-2 focus:ring-blue-400">
                        <?php
                        $query = "SELECT * FROM dokter";
                        $result = mysqli_query($conn, $query);

                        while ($row = mysqli_fetch_assoc($result)) {
                            $id_dokter = $row["id"];
                            $dokter = $row["username"];
                            echo "<option value='$id_dokter'>$dokter</option>";
                        }
                        ?>
                    </select>
                    <button type="submit" name="submit">Tambah Data</button>
                </form>

                <section class="table-container">
                    <h1 class="mb-5 text-2xl font-medium">Daftar Pasien</h1>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pasien</th>
                                <th>Alamat</th>
                                <th>No. KTP</th>
                                <th>No. Telepon</th>
                                <th>No. RM</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $index = 1; ?>
                            <?php foreach ($pasien as $item) : ?>
                                <tr>
                                    <td><?= $index ?></td>
                                    <td><?= $item["nama"] ?></td>
                                    <td><?= $item["alamat"] ?></td>
                                    <td><?= $item["no_ktp"] ?></td>
                                    <td><?= $item["no_hp"] ?></td>
                                    <td><?= $item["no_rm"] ?></td>
                                    <td class="actions">
                                        <a href="edit_pasien.php?id=<?= $item["id"] ?>" class="edit">Edit</a>
                                        <a href="hapus_pasien.php?id=<?= $item["id"] ?>" class="delete">Delete</a>
                                    </td>
                                </tr>
                                <?php $index++ ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
            </article>
        </main>
    </div>

</body>

</html>