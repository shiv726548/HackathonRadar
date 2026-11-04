<?php
require_once 'config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $db->prepare('SELECT id, name, email, password, role FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && $user['role'] === 'user' && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = 'user';
        header('Location: index.php');
        exit;
    }
    $error = 'Email or password is incorrect. Admins should use the admin login page.';
}

render_header('Log in');
?>
<section class="form-panel">
    <h1>Log in</h1>
    <?php if (isset($_GET['registered'])): ?><p class="notice">Account created. You can now log in.</p><?php endif; ?>
    <?php if ($error): ?><p class="notice notice-error"><?= h($error) ?></p><?php endif; ?>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <label>Email<input type="email" name="email" required value="<?= h($_POST['email'] ?? '') ?>"></label>
        <label>Password<input type="password" name="password" required></label>
        <button class="button" type="submit">Log in</button>
    </form>
    <p>Need an account? <a href="register.php">Register</a>.</p>
</section>
<?php render_footer(); ?>
