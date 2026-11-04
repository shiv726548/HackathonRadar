<?php
require_once 'config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($name === '' || strlen($name) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a name and a valid email address.';
    } elseif (strlen($password) < 8) {
        $error = 'Use a password with at least 8 characters.';
    } elseif ($password !== $confirm) {
        $error = 'The passwords do not match.';
    } else {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, "user")');
            $stmt->bind_param('sss', $name, $email, $hash);
            $stmt->execute();
            header('Location: login.php?registered=1');
            exit;
        } catch (mysqli_sql_exception $e) {
            $error = 'That email may already be registered. Please try logging in.';
        }
    }
}

render_header('Register');
?>
<section class="form-panel">
    <h1>Create an account</h1>
    <p class="muted">Register to save opportunities to your dashboard.</p>
    <?php if ($error): ?><p class="notice notice-error"><?= h($error) ?></p><?php endif; ?>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <label>Full name<input name="name" maxlength="100" required value="<?= h($_POST['name'] ?? '') ?>"></label>
        <label>Email<input type="email" name="email" required value="<?= h($_POST['email'] ?? '') ?>"></label>
        <label>Password<input type="password" name="password" minlength="8" required></label>
        <label>Confirm password<input type="password" name="confirm_password" minlength="8" required></label>
        <button class="button" type="submit">Register</button>
    </form>
    <p>Already registered? <a href="login.php">Log in</a>.</p>
</section>
<?php render_footer(); ?>
