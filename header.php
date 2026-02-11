<?php
// header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Movie Critics Network</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-950 text-gray-200 min-h-screen flex flex-col">

<!-- HEADER -->
<header class="bg-black border-b border-blue-500 shadow-lg">
  <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">

    <!-- LOGO -->
    <a href="/movie_critics/index.php" class="text-2xl font-bold text-blue-400">
      🎬 Movie Critics
    </a>

    <!-- NAVIGATION -->
    <nav class="space-x-6 text-sm md:text-base">
      <a href="/movie_critics/index.php"
         class="hover:text-blue-400 transition">
         Home
      </a>

      <?php if (isset($_SESSION['user'])): ?>

        <a href="/movie_critics/profile.php"
           class="hover:text-blue-400 transition">
           Profile
        </a>

        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
          <a href="/movie_critics/admin/add_movie.php"
             class="hover:text-yellow-400 transition">
             Add Movie
          </a>
        <?php endif; ?>

        <a href="/movie_critics/auth/logout.php"
           class="text-red-400 hover:text-red-500 transition">
           Logout
        </a>

      <?php else: ?>

        <a href="/movie_critics/auth/login.php"
           class="hover:text-blue-400 transition">
           Login
        </a>

        <a href="/movie_critics/auth/register.php"
           class="bg-blue-500 px-4 py-1 rounded hover:bg-blue-600 transition">
           Register
        </a>

      <?php endif; ?>
    </nav>

  </div>
</header>

<!-- MAIN CONTENT START -->
<main class="flex-grow max-w-7xl mx-auto w-full px-4 py-6">
