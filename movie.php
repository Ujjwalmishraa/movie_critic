<?php
require "config/db.php";
require "includes/header.php";

/* -------- VALIDATE MOVIE ID -------- */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid movie");
}

$movie_id = $_GET['id'];

/* -------- FETCH MOVIE -------- */
$stmt = $pdo->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->execute([$movie_id]);
$movie = $stmt->fetch();

if (!$movie) {
    die("Movie not found");
}

/* -------- FETCH REVIEWS + USERS -------- */
$reviewsStmt = $pdo->prepare("
    SELECT r.*, u.name, u.profile_photo
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    WHERE r.movie_id = ?
    ORDER BY r.created_at DESC
");
$reviewsStmt->execute([$movie_id]);

/* -------- CALCULATE NETWORK AVERAGE -------- */
$avgStmt = $pdo->prepare("SELECT AVG(avg_score) FROM reviews WHERE movie_id=?");
$avgStmt->execute([$movie_id]);
$networkAvg = $avgStmt->fetchColumn();
?>

<!-- MOVIE DETAILS -->
<div class="grid md:grid-cols-2 gap-8 mb-10">

    <!-- POSTER -->
    <img src="/movie_critics/uploads/posters/<?php echo htmlspecialchars($movie['poster']); ?>"
         alt="Poster"
         class="rounded-xl shadow-lg w-full max-h-[450px] object-cover">

    <!-- INFO -->
    <div>
        <h2 class="text-3xl font-bold text-blue-400 mb-3">
            <?php echo htmlspecialchars($movie['title']); ?>
        </h2>

        <p class="text-gray-400 mb-4">
            <?php echo nl2br(htmlspecialchars($movie['description'])); ?>
        </p>

        <div class="text-lg font-semibold text-yellow-400">
            ⭐ Network Rating:
            <?php echo $networkAvg ? number_format($networkAvg, 1) : "No ratings yet"; ?>/5
        </div>

        <!-- ADD REVIEW BUTTON -->
        <?php if (isset($_SESSION['user'])): ?>
            <a href="/movie_critics/reviews/add_review.php?movie=<?php echo $movie_id; ?>"
               class="inline-block mt-5 bg-blue-500 px-5 py-2 rounded font-semibold hover:bg-blue-600 transition">
               ✍️ Write a Review
            </a>
        <?php else: ?>
            <p class="mt-5 text-red-400 text-sm">
                Login to give your review
            </p>
        <?php endif; ?>
    </div>

</div>

<hr class="border-gray-700 mb-8">

<!-- REVIEWS SECTION -->
<h3 class="text-2xl font-bold mb-6">🗣 Critics Reviews</h3>

<?php if ($reviewsStmt->rowCount() === 0): ?>
    <p class="text-gray-400">No reviews yet. Be the first critic!</p>
<?php endif; ?>

<?php while ($r = $reviewsStmt->fetch()): ?>
<div class="bg-gray-900 p-5 rounded-xl mb-5 shadow">

    <!-- CRITIC INFO -->
    <div class="flex items-center gap-3 mb-2">
        <img src="/movie_critics/uploads/profiles/<?php echo htmlspecialchars($r['profile_photo'] ?? 'default.png'); ?>"
             class="w-10 h-10 rounded-full object-cover border border-blue-400">

        <span class="font-semibold text-blue-400">
            <?php echo htmlspecialchars($r['name']); ?>
        </span>
    </div>

    <!-- AVG RATING -->
    <div class="text-sm text-yellow-400 mb-2">
        ⭐ <?php echo number_format($r['avg_score'], 1); ?>/5
    </div>

    <!-- COMMENT -->
    <p class="text-gray-300 italic mb-4">
        “<?php echo nl2br(htmlspecialchars($r['comment'])); ?>”
    </p>

    <!-- CATEGORY RATINGS -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-2 text-xs text-gray-400">
        <span>Acting: <?php echo $r['acting']; ?>/5</span>
        <span>Story: <?php echo $r['story']; ?>/5</span>
        <span>Direction: <?php echo $r['direction']; ?>/5</span>
        <span>Visuals: <?php echo $r['visuals']; ?>/5</span>
        <span>Sound: <?php echo $r['sound']; ?>/5</span>
    </div>

</div>
<?php endwhile; ?>

<?php require "includes/footer.php"; ?>
