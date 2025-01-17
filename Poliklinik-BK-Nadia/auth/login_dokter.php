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

 
    $result_admin = mysqli_query($conn, "SELECT * FROM dokter WHERE username = '$username'"); 
    // Cek username
    if (mysqli_num_rows($result_admin) === 1) { //cek ada berapa baris yang ditemukan (pasti 1)
        // Cek Password
        $row = mysqli_fetch_assoc($result_admin);
        if ($password === $row["password"]) {
            // Set Session
            $_SESSION["login"] = "true";
            $_SESSION["username"] = $username;
            $_SESSION["id"] = $row["id"];
            header("Location: ../pages/dokter/dashboard_dokter.php?username=$username");
            exit;
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
    <title>Login Dokter</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Baby Blue Gradient */
        .gradient-bg {
            background: linear-gradient(135deg, #A0D8FF, #66C2FF);
        }

        /* Custom Font */
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="flex h-screen gradient-bg">
    <!-- Left Image Section -->
    <figure class="w-1/2 flex justify-center items-center">
        <img src="../assets/images/dokter.png" alt="Doctor Illustration" class="max-w-full h-auto">
    </figure>

    <!-- Right Login Form Section -->
    <main class="w-1/2 flex justify-center items-center bg-white shadow-xl rounded-xl p-10">
        <a href="../index.php" class="absolute top-8 left-8">
            <img src="../assets/icons/arrow-left.svg" alt="Back" width="30px" class="invert">
        </a>

        <form action="" method="post" class="w-full max-w-sm">
            <h1 class="text-center text-3xl font-semibold text-[#004079] mb-6">Login Dokter</h1>
            
            <!-- Username & Password Input -->
            <div class="flex flex-col gap-6">
                <input type="text" name="username" required placeholder="Username"
                    class="bg-gray-200 px-5 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#66C2FF]">
                <input type="password" name="password" required placeholder="Password"
                    class="bg-gray-200 px-5 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#66C2FF]">
                <input type="hidden" name="role" value="dokter">

                <?php if (isset($error)) : ?>
                    <h2 class="text-center text-red-500 mt-3">Username / Password salah!</h2>
                <?php endif; ?>
            </div>

            <!-- Login Button -->
            <button type="submit" name="login"
                class="w-full mt-6 bg-[#004079] text-white py-3 font-medium rounded-lg hover:bg-[#003057] transition-all">
                Login
            </button>
        </form>
    </main>
</body>

</html>
