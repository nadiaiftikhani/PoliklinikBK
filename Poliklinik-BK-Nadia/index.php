<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to PRECLINIC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(to right, #A7C7E7, #E3F2FD); /* Baby blue gradient */
            color: #1A1A1A;
        }

        a {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        a:hover {
            transform: scale(1.05);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .image-shadow {
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            background: none; /* Ensure no background for image container */
        }

        .btn-group {
            flex-direction: column;
        }

        @media (min-width: 768px) {
            .btn-group {
                flex-direction: row;
            }
        }
    </style>
</head>

<body class="flex flex-col-reverse md:flex-row items-center h-screen px-10 md:px-28">
    <!-- Left Side -->
    <div class="flex flex-col gap-5 items-center md:items-start text-center md:text-left">
        <h1 class="text-[#1A73E8] text-5xl md:text-6xl font-bold leading-snug">
            Welcome to <span class="text-[#A7C7E7]">PRECLINIC</span>
        </h1>
        <p class="text-gray-800 text-lg md:text-xl">
            Your trusted healthcare solution for you and your family. Enjoy professional medical services with ease and efficiency!
        </p>
        <div class="btn-group flex gap-5 mt-5 justify-center md:justify-start">
            <a href="auth/admin.php" class="flex items-center justify-center bg-[#A7C7E7] text-white px-10 py-3 font-medium rounded-lg hover:bg-[#81BFE7]">
                Login Admin
            </a>
            <a href="auth/login_dokter.php" class="flex items-center justify-center border border-[#A7C7E7] text-[#1A73E8] px-10 py-3 font-medium rounded-lg hover:bg-[#A7C7E7] hover:text-white">
                Login Dokter
            </a>
            <a href="auth/login_pasien.php" class="flex items-center justify-center border border-[#A7C7E7] text-[#1A73E8] px-10 py-3 font-medium rounded-lg hover:bg-[#A7C7E7] hover:text-white">
                Login Pasien
            </a>
        </div>
    </div>
    <!-- Left Side End -->

    <!-- Right Side -->
    <div class="flex justify-center md:justify-end mb-10 md:mb-0">
        <img src="assets/images/dokter.png" width="500px" alt="Healthcare Illustration" class="image-shadow">
    </div>
    <!-- Right Side End -->
</body>

</html>
