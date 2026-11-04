<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $db = new mysqli('localhost', 'root', '', 'hackathon_radar');
    $db->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    exit('Database connection failed. Check config.php and make sure MySQL is running and database.sql was imported.');
}

function h($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function check_csrf(): void
{
    $sent = $_POST['csrf_token'] ?? '';
    if (!is_string($sent) || !hash_equals(csrf_token(), $sent)) {
        http_response_code(400);
        exit('Invalid form token. Please go back, refresh the page, and try again.');
    }
}

function signed_in(): bool
{
    return isset($_SESSION['user_id']);
}

function require_admin(): void
{
    if (!signed_in() || ($_SESSION['role'] ?? '') !== 'admin') {
        header('Location: admin_login.php');
        exit;
    }
}

function opportunity_table(string $type): string
{
    if ($type === 'hackathon') {
        return 'hackathons';
    }
    if ($type === 'competition') {
        return 'competitions';
    }
    throw new InvalidArgumentException('Invalid opportunity type.');
}

function get_opportunities(mysqli $db, string $type, string $search = '', int $limit = 0): array
{
    $table = opportunity_table($type);
    $search = trim($search);

    if ($search !== '') {
        $sql = "SELECT * FROM $table
                WHERE title LIKE ? OR organizer LIKE ? OR description LIKE ?
                ORDER BY deadline ASC";
        if ($limit > 0) {
            $sql .= ' LIMIT ' . $limit;
        }

        $stmt = $db->prepare($sql);
        $like = '%' . $search . '%';
        $stmt->bind_param('sss', $like, $like, $like);
    } else {
        $sql = "SELECT * FROM $table ORDER BY deadline ASC";
        if ($limit > 0) {
            $sql .= ' LIMIT ' . $limit;
        }
        $stmt = $db->prepare($sql);
    }

    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function opportunity_card(array $item, string $type): void
{
    $label = $type === 'hackathon' ? 'Hackathon' : 'Competition';
    ?>
    <article class="card">
        <span class="badge"><?= h($label) ?></span>
        <h3><?= h($item['title']) ?></h3>
        <p class="muted">Organized by <?= h($item['organizer']) ?></p>
        <p><?= h($item['description']) ?></p>
        <p><strong>Deadline:</strong> <?= h($item['deadline']) ?></p>
        <?php if (!empty($item['prize'])): ?>
            <p><strong>Prize:</strong> <?= h($item['prize']) ?></p>
        <?php endif; ?>
        <a class="button button-secondary"
           href="details.php?type=<?= h($type) ?>&amp;id=<?= (int)$item['id'] ?>">View details</a>
    </article>
    <?php
}

function render_header(string $title): void
{
    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= h($title) ?> | Hackathon Radar</title>
        <link rel="stylesheet" href="assets/style.css">
        <script src="assets/script.js" defer></script>
    </head>
    <body>
    <header class="site-header">
        <a class="brand" href="index.php">
            <svg class="brand-icon" viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="12" cy="12" r="9"></circle>
                <circle cx="12" cy="12" r="5"></circle>
                <circle cx="12" cy="12" r="1"></circle>
                <path d="M12 12 19 5"></path>
            </svg>
            <span>Hackathon Radar</span>
        </a>
        <nav class="nav-links" aria-label="Main navigation">
            <a href="hackathons.php">Hackathons</a>
            <a href="competitions.php">Competitions</a>
            <?php if (signed_in()): ?>
                <a href="dashboard.php">Dashboard</a>
                <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                    <a href="admin.php">Admin</a>
                <?php endif; ?>
                <a href="logout.php">Log out</a>
            <?php else: ?>
                <a href="login.php">Log in</a>
                <a href="register.php">Register</a>
                <a href="admin_login.php">Admin</a>
            <?php endif; ?>
            <button type="button" class="theme-toggle" id="themeToggle" aria-label="Toggle light and dark theme">◐</button>
        </nav>
    </header>
    <main class="container">
    <?php
}

function render_footer(): void
{
    ?>
    </main>
    <footer class="site-footer">
        <p>Hackathon Radar · Student opportunity listings</p>
        <p class="muted">Demo entries are for demonstration only. Verify event details with the official organizer.</p>
    </footer>
    </body>
    </html>
    <?php
}
