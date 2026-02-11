<?php
require_once __DIR__ . "/../config/db.php";

/* ---------- SECURITY ---------- */
if (!isset($_SESSION['user'])) {
    header("Location: /movie_critics/auth/login.php");
    exit;
}

if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    die("403 Forbidden – Admin Only");
}
/* -------------------------------- */

require_once __DIR__ . "/../includes/header.php";

/* ---------- FORM SUBMIT ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    if (empty($title)) {
        die("Movie title required");
    }

    /* Poster Upload */
    $posterName = time() . "_" . basename($_FILES['poster']['name']);
    $uploadPath = __DIR__ . "/../uploads/posters/" . $posterName;

    if (!move_uploaded_file($_FILES['poster']['tmp_name'], $uploadPath)) {
        die("Poster upload failed");
    }

    /* Insert Movie */
    $stmt = $pdo->prepare(
        "INSERT INTO movies (title, poster, description) VALUES (?, ?, ?)"
    );
    $stmt->execute([$title, $posterName, $description]);

    header("Location: /movie_critics/index.php");
    exit;
}
?>

<div class="max-w-xl mx-auto bg-gray-900 p-6 rounded-xl shadow-lg">
    <h2 class="text-2xl text-blue-400 font-bold mb-4 text-center">
        🎬 Add New Movie
    </h2>

    <form method="post" enctype="multipart/form-data">

        <label class="block mb-2">Movie Title</label>
        <input type="text" name="title" required
               class="w-full p-2 mb-4 bg-black rounded">

        <label class="block mb-2">Description</label>
        <textarea name="description" rows="4"
                  class="w-full p-2 mb-4 bg-black rounded"></textarea>

        <label class="block mb-2">Poster</label>
        <input type="file" name="poster" required class="mb-4">

        <button class="w-full bg-blue-500 py-2 rounded font-bold">
            ➕ Add Movie
        </button>

    </form>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
