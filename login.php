<?php
require "../config/db.php";

if (isset($_SESSION['user'])) {
    header("Location: /movie_critics/index.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header("Location: /movie_critics/index.php");
        exit;
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login | Movie Critics</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-950 flex items-center justify-center min-h-screen text-gray-200">

<div class="bg-gray-900 p-6 rounded-xl shadow-lg w-full max-w-md">

    <h2 class="text-2xl font-bold text-blue-400 text-center mb-4">
        🔐 Login
    </h2>

    <?php if ($error): ?>
        <p class="text-red-400 text-sm mb-3 text-center">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>

    <form method="post">

        <label class="block mb-2">Email</label>
        <input type="email" name="email" required
               class="w-full p-2 mb-4 bg-black rounded">

        <label class="block mb-2">Password</label>
        <input type="password" name="password" required
               class="w-full p-2 mb-4 bg-black rounded">

        <button class="w-full bg-blue-500 py-2 rounded font-semibold hover:bg-blue-600">
            Login
        </button>

    </form>

    <!-- REGISTER NAVIGATION -->
    <p class="text-center text-sm text-gray-400 mt-4">
        Don’t have an account?
        <a href="/movie_critics/auth/register.php"
           class="text-blue-400 hover:underline">
           Register here
        </a>
    </p>

</div>

</body>
</html>
