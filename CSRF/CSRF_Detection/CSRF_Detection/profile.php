<?php
define('CAT_BLOG_SHARED', true);
require_once __DIR__ . '/login.php';
$user = require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    db_query('UPDATE UserAccounts SET user_email = ? WHERE user_id = ?', [posted('new_email'), $user['user_id']]);
    $_SESSION['profile_notice'] = 'Email change successful!';
    redirect('profile.php');
}
page_start('My profile');
?>
<header class="header"><a href="index.php">← Back to blog</a><strong>My profile</strong></header>
<section class="card"><h1>My profile</h1>
<?php if (isset($_SESSION['profile_notice'])): ?><p class="notice" role="status"><?= h($_SESSION['profile_notice']) ?></p><?php unset($_SESSION['profile_notice']); endif; ?>
<dl><dt>Username</dt><dd><?= h($user['user_name']) ?></dd><dt>Email</dt><dd><?= h($user['user_email']) ?></dd><dt>User ID</dt><dd><?= (int) $user['user_id'] ?></dd></dl>
<form method="post" action="profile.php">

<label for="new_email">Change email address</label>
<input id="new_email" name="new_email" type="text" autocomplete="email" value="<?= h($_SERVER['REQUEST_METHOD'] === 'POST' ? posted('new_email') : $user['user_email']) ?>">
<button type="submit">Change email</button>
</form>
<p></p>
<div class="actions"><a href="index.php">Go to blog</a><form method="post" action="login.php"><input type="hidden" name="action" value="logout"><button type="submit">Logout</button></form></div>
</section>
<?php page_end(); ?>
