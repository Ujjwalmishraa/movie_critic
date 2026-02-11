<?php
require "config/db.php";
require "includes/header.php";

/* Fetch movies */
$sql = "
SELECT m.*, IFNULL(AVG(r.avg_score),0) AS avg_rating
FROM movies m
LEFT JOIN reviews r ON m.id = r.movie_id
GROUP BY m.id
ORDER BY m.id DESC
";
$movies = $pdo->query($sql)->fetchAll();
?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-bold">🎞 Latest Movies</h2>

    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
        <a href="/movie_critics/admin/add_movie.php"
           class="bg-blue-500 px-4 py-2 rounded font-semibold hover:bg-blue-600">
           ➕ Add Movie
        </a>
    <?php endif; ?>
</div>

<?php if (count($movies) === 0): ?>
    <p class="text-gray-400">No movies added yet.</p>
<?php else: ?>
<div class="grid grid-cols-2 md:grid-cols-4 gap-6">
<?php foreach ($movies as $m): ?>
    <div class="bg-gray-900 rounded-xl overflow-hidden shadow-lg">
        <img src="/movie_critics/uploads/posters/<?php echo htmlspecialchars($m['poster']); ?>"
             class="h-56 w-full object-cover">

        <div class="p-4">
            <h3 class="font-bold">
                <?php echo htmlspecialchars($m['title']); ?>
            </h3>

            <p class="text-blue-400 text-sm">
                ⭐ <?php echo number_format($m['avg_rating'], 1); ?>/5
            </p>

            <a href="/movie_critics/movie.php?id=<?php echo $m['id']; ?>"
               class="text-sm text-gray-400 hover:text-blue-400">
               View Details →
            </a>
        </div>
    </div>
<?php endforeach; ?>
</div>
<?php endif; ?>

<?php require "includes/footer.php"; ?>
