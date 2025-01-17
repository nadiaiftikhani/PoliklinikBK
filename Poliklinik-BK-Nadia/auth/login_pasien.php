<?php
session_start();

// if (isset($_SESSION["login"])) {
//     header("Location: ../index.php");
//     exit;
// }

require '../functions/connect_database.php';

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM pasien WHERE username = '$username'");

    // Cek username
    if (mysqli_num_rows($result) === 1) {
        // Cek Password
        $row = mysqli_fetch_assoc($result);
        if ($password === $row["password"]) {
            // Set Session
            $_SESSION["login"] = "true";
            $_SESSION["username"] = $username;
            $_SESSION["no_rm"] = $row["no_rm"];
            $_SESSION["id"] = $row["id"];
            header("Location: ../pages/pasien/dashboard_pasien.php?username=$username");
            exit;
        }
    }

    if ($username === 'admin') {
        if ($password === '123') {
            $_SESSION["login"] = "true";
            $_SESSION["username"] = $username;
            header("Location: ../pages/admin/dashboard_admin.php");
        }
    }

    $error = true;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pasien</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(to right, #A7C7E7, #E3F2FD);
        }
    </style>
</head>

<body class="flex h-screen">
    <figure class="basis-1/2 flex justify-center items-center h-full">
        <img src="../assets/images/dokter.png" alt="Illustration of Patient" width="60%">
    </figure>

    <main class="basis-1/2 relative flex justify-center items-center bg-white shadow-lg rounded-lg">
        <a href="../index.php" class="absolute top-8 left-10">
            <img src="../assets/icons/arrow-left.svg" alt="Back" width="30px">
        </a>
        <form action="" method="post" class="w-full px-20">
            <h1 class="text-center text-3xl font-semibold text-[#1A73E8]">Login Pasien</h1>
            <div class="flex flex-col gap-5 mt-7">
                <input type="text" name="username" required placeholder="Username"
                    class="bg-gray-200 px-5 py-3 outline-none rounded-lg shadow-inner">

                <input type="password" name="password" required placeholder="Password"
                    class="bg-gray-200 px-5 py-3 outline-none rounded-lg shadow-inner">

                <?php if (isset($error)) : ?>
                    <h1 class="text-red-500 text-center">Username / Password salah!</h1>
                <?php endif; ?>

                <button type="submit" name="login"
                    class="mt-3 bg-gradient-to-r from-[#7EB6FF] to-[#3D9FFF] text-white py-3 text-lg font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                    Login
                </button>

                <div class="flex justify-center gap-2 mt-4">
                    <h1 class="text-gray-600">Belum punya akun?</h1>
                    <a href="registrasi_pasien.php" class="text-[#1A73E8] font-medium underline">Registrasi</a>
                </div>
            </div>
        </form>
    </main>
</body>

</html>
