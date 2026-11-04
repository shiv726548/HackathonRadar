<?php
require_once 'config.php';
if (!signed_in() || ($_SESSION['role'] ?? '') !== 'user') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $savedId = filter_input(INPUT_POST, 'saved_id', FILTER_VALIDATE_INT);
    if ($savedId) {
        $stmt = $db->prepare('DELETE FROM saved_items WHERE id = ? AND user_id = ?');
        $userId = (int)$_SESSION['user_id'];
        $stmt->bind_param('ii', $savedId, $userId);
        $stmt->execute();
    }
    header('Location: dashboard.php');
    exit;
}

$userId = (int)$_SESSION['user_id'];
$items = [];
$stmt = $db->prepare(
    "SELECT s.id AS saved_id, s.item_type, h.id, h.title, h.organizer, h.description, h.deadline, h.prize
     FROM saved_items s JOIN hackathons h ON s.item_id = h.id
     WHERE s.user_id = ? AND s.item_type = 'hackathon'
     UNION ALL
     SELECT s.id AS saved_id, s.item_type, c.id, c.title, c.organizer, c.description, c.deadline, c.prize
     FROM saved_items s JOIN competitions c ON s.item_id = c.id
     WHERE s.user_id = ? AND s.item_type = 'competition'
     ORDER BY deadline ASC"
);
$stmt->bind_param('ii', $userId, $userId);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

render_header('Dashboard');
?>
<section class="page-intro">
    <p class="eyebrow">Your account</p>
    <h1>Welcome, <?= h($_SESSION['user_name']) ?></h1>
    <p>Your saved hackathons and competitions are listed here.</p>
</section>
<?php if (!$items): ?>
    <p class="notice">You haven't saved any opportunities yet. Browse the <a href="hackathons.php">hackathons</a> or <a href="competitions.php">competitions</a>.</p>
<?php else: ?>
    <div class="card-grid">
        <?php foreach ($items as $item): ?>
            <article class="card">
                <span class="badge"><?= $item['item_type'] === 'hackathon' ? 'Hackathon' : 'Competition' ?></span>
                <h3><?= h($item['title']) ?></h3>
                <p class="muted">Organized by <?= h($item['organizer']) ?></p>
                <p><?= h($item['description']) ?></p>
                <p><strong>Deadline:</strong> <?= h($item['deadline']) ?></p>
                <div class="button-row">
                    <a class="button button-secondary" href="details.php?type=<?= h($item['item_type']) ?>&amp;id=<?= (int)$item['id'] ?>">View details</a>
                    <form method="post">
                        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                        <input type="hidden" name="saved_id" value="<?= (int)$item['saved_id'] ?>">
                        <button class="button button-danger" type="submit">Remove</button>
                    </form>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php render_footer(); ?>
