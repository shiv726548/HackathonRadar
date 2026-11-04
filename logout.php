<?php
require_once 'config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $db->prepare('SELECT id, name, password, role FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && $user['role'] === 'admin' && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = 'admin';
        header('Location: admin.php');
        exit;
    }
    $error = 'Admin email or password is incorrect.';
}

render_header('Admin login');
?>
<section class="form-panel">
    <h1>Administrator login</h1>
    <p class="muted">Admin accounts must be created by an administrator in the database.</p>
    <?php if ($error): ?><p class="notice notice-error"><?= h($error) ?></p><?php endif; ?>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <label>Email<input type="email" name="email" required></label>
        <label>Password<input type="password" name="password" required></label>
        <button class="button" type="submit">Log in as admin</button>
    </form>
</section>
<?php render_footer(); ?>
