<?php
require "config/db.php";
include "includes/header.php";

if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit;
}

$user = $_SESSION['user'];

// Review count
$stmt = $pdo->prepare("SELECT COUNT(*) FROM reviews WHERE user_id = ?");
$stmt->execute([$user['id']]);
$reviewCount = $stmt->fetchColumn();

// Upload profile photo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {

    $fileName = time() . "_" . $_FILES['photo']['name'];
    $target = "uploads/profiles/" . $fileName;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
        $update = $pdo->prepare("UPDATE users SET profile_photo=? WHERE id=?");
        $update->execute([$fileName, $user['id']]);

        $_SESSION['user']['profile_photo'] = $fileName;
        header("Location: profile.php");
        exit;
    }
}

$photo = $_SESSION['user']['profile_photo'] ?? 'default.png';
?>

<div class="bg-gray-900 p-6 rounded-xl shadow-lg max-w-xl mx-auto">

  <div class="flex flex-col items-center gap-4">

    <img src="/movie_critics/uploads/profiles/<?php echo htmlspecialchars($photo); ?>"
         class="w-28 h-28 rounded-full border-2 border-blue-400 object-cover">

    <h2 class="text-2xl text-blue-400 font-bold">
        <?php echo htmlspecialchars($user['name']); ?>
    </h2>

    <p class="text-gray-400"><?php echo htmlspecialchars($user['email']); ?></p>

    <span class="bg-blue-500 px-4 py-1 rounded-full text-sm">
        Reviews: <?php echo $reviewCount; ?>
    </span>

    <!-- UPLOAD FORM -->
    <form method="post" enctype="multipart/form-data" class="mt-4">
        <input type="file" name="photo" required
               class="text-sm text-gray-300">
        <button class="block mt-3 bg-blue-500 px-4 py-1 rounded">
            Upload Photo
        </button>
    </form>

  </div>
</div>

<?php include "includes/footer.php"; ?>
