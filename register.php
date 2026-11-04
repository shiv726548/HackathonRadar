<?php
require_once 'config.php';

$type = $_GET['type'] ?? '';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
try {
    $table = opportunity_table($type);
} catch (InvalidArgumentException $e) {
    http_response_code(404);
    exit('Opportunity not found.');
}
if (!$id) {
    http_response_code(404);
    exit('Opportunity not found.');
}

$stmt = $db->prepare("SELECT * FROM $table WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();
if (!$item) {
    http_response_code(404);
    exit('Opportunity not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    if (!signed_in()) {
        header('Location: login.php');
        exit;
    }

    $userId = (int)$_SESSION['user_id'];
    $check = $db->prepare('SELECT id FROM saved_items WHERE user_id = ? AND item_id = ? AND item_type = ?');
    $check->bind_param('iis', $userId, $id, $type);
    $check->execute();
    $saved = $check->get_result()->fetch_assoc();

    if ($saved) {
        $delete = $db->prepare('DELETE FROM saved_items WHERE user_id = ? AND item_id = ? AND item_type = ?');
        $delete->bind_param('iis', $userId, $id, $type);
        $delete->execute();
    } else {
        $insert = $db->prepare('INSERT IGNORE INTO saved_items (user_id, item_id, item_type) VALUES (?, ?, ?)');
        $insert->bind_param('iis', $userId, $id, $type);
        $insert->execute();
    }
    header('Location: details.php?type=' . urlencode($type) . '&id=' . $id);
    exit;
}

$isSaved = false;
if (signed_in()) {
    $userId = (int)$_SESSION['user_id'];
    $check = $db->prepare('SELECT id FROM saved_items WHERE user_id = ? AND item_id = ? AND item_type = ?');
    $check->bind_param('iis', $userId, $id, $type);
    $check->execute();
    $isSaved = (bool)$check->get_result()->fetch_assoc();
}

render_header($item['title']);
?>
<section class="detail-panel">
    <span class="badge"><?= $type === 'hackathon' ? 'Hackathon' : 'Competition' ?></span>
    <h1><?= h($item['title']) ?></h1>
    <p class="muted">Organized by <?= h($item['organizer']) ?></p>
    <p><?= nl2br(h($item['description'])) ?></p>
    <p><strong>Deadline:</strong> <?= h($item['deadline']) ?></p>
    <?php if ($item['prize'] !== ''): ?><p><strong>Prize:</strong> <?= h($item['prize']) ?></p><?php endif; ?>
    <div class="button-row">
        <a class="button" href="<?= h($item['registration_url']) ?>" target="_blank" rel="noopener noreferrer">Official registration link</a>
        <?php if (signed_in() && ($_SESSION['role'] ?? '') === 'user'): ?>
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                <button class="button button-secondary" type="submit"><?= $isSaved ? 'Remove from saved' : 'Save opportunity' ?></button>
            </form>
        <?php elseif (!signed_in()): ?>
            <a class="button button-secondary" href="login.php">Log in to save</a>
        <?php endif; ?>
    </div>
    <p class="muted small">Check dates and event details with the official organizer before registering.</p>
</section>
<?php render_footer(); ?>
