<?php
require "../config/db.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$movie_id = $_GET['movie'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $acting    = $_POST['acting'];
    $story     = $_POST['story'];
    $direction = $_POST['direction'];
    $visuals   = $_POST['visuals'];
    $sound     = $_POST['sound'];
    $comment   = trim($_POST['comment']);

    $avg = ($acting + $story + $direction + $visuals + $sound) / 5;

    $stmt = $pdo->prepare("
        INSERT INTO reviews 
        (user_id, movie_id, acting, story, direction, visuals, sound, avg_score, comment)
        VALUES (?,?,?,?,?,?,?,?,?)
    ");

    $stmt->execute([
        $_SESSION['user']['id'],
        $movie_id,
        $acting, $story, $direction, $visuals, $sound,
        $avg,
        $comment
    ]);

    header("Location: ../movie.php?id=$movie_id");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Review</title>
<script src="https://cdn.tailwindcss.com"></script>

<style>
.star { cursor:pointer; font-size:1.6rem; color:#555; }
.star.active { color:#3b82f6; }
</style>
</head>

<body class="bg-gray-950 flex justify-center items-center min-h-screen text-gray-200">

<form method="post" class="bg-gray-900 p-6 rounded-xl w-full max-w-md">
<h2 class="text-2xl text-blue-400 text-center mb-4">
🎯 Critic Review
</h2>

<?php
$fields = [
    "acting" => "Acting",
    "story" => "Story",
    "direction" => "Direction",
    "visuals" => "Visuals",
    "sound" => "Sound"
];
foreach ($fields as $name => $label):
?>
<div class="mt-4">
  <label class="block mb-1"><?= $label ?></label>
  <div class="flex gap-1" data-name="<?= $name ?>">
    <?php for($i=1;$i<=5;$i++): ?>
      <span class="star" data-value="<?= $i ?>">★</span>
    <?php endfor; ?>
  </div>
  <input type="hidden" name="<?= $name ?>" value="3">
</div>
<?php endforeach; ?>

<!-- COMMENT BOX -->
<div class="mt-6">
  <label class="block mb-2">Critic Comment</label>
  <textarea name="comment" rows="4" required
            placeholder="Write your opinion about the movie..."
            class="w-full p-2 bg-black rounded text-sm"></textarea>
</div>

<button class="mt-6 w-full bg-blue-500 py-2 rounded font-bold">
Submit Review
</button>
</form>

<script>
document.querySelectorAll('[data-name]').forEach(group=>{
  const input = group.nextElementSibling;
  group.querySelectorAll('.star').forEach(star=>{
    star.onclick = ()=>{
      const val = star.dataset.value;
      input.value = val;
      group.querySelectorAll('.star').forEach(s=>{
        s.classList.toggle('active', s.dataset.value <= val);
      });
    };
  });
});
</script>

</body>
</html>
