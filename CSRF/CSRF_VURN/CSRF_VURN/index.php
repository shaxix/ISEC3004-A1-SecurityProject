<?php
define('CAT_BLOG_SHARED', true);
require_once __DIR__ . '/login.php';
$user = require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    db_query('INSERT INTO SimpleCatComments (user_id, comment_text) VALUES (?, ?)', [$user['user_id'], posted('comment')]);
    redirect('index.php#comments');
}
$count = (int) $conn->query('SELECT COUNT(*) FROM SimpleCatComments')->fetch_row()[0];
$pages = max(1, (int) ceil($count / 20));
$page = min($pages, max(1, (int) (filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1)));
$offset = ($page - 1) * 20;
$comments = $conn->query("SELECT c.comment_text, c.created_at, u.user_name FROM SimpleCatComments c JOIN UserAccounts u ON u.user_id = c.user_id ORDER BY c.comment_id DESC LIMIT 20 OFFSET $offset")->fetch_all(MYSQLI_ASSOC);
page_start('My cat blog');
?>
<header class="header"><strong>My cat blog</strong><a class="profile-icon" href="profile.php" aria-label="Open your profile"><span aria-hidden="true">👤</span> Profile</a></header>
<article class="card"><h1>Look at my cat!</h1><img class="cat-picture" src="https://static.vecteezy.com/system/resources/previews/074/065/378/non_2x/a-cute-fluffy-tabby-kitten-with-big-eyes-reaches-out-with-its-pink-paw-pads-photo.jpg"></article>
<section class="card comments" id="comments"><h2>Comments</h2>
<form method="post" action="index.php#comments">
<label for="comment">Add a comment</label><textarea id="comment" name="comment" rows="3" placeholder="Write a comment…"><?= h(posted('comment')) ?></textarea>
<button type="submit">Post comment</button></form>
<?php if (!$comments): ?><p class="muted">No comments yet.</p><?php endif; ?>
<?php foreach ($comments as $comment): ?><article class="comment"><strong><?= h($comment['user_name']) ?></strong> <small><?= h($comment['created_at']) ?></small><div><?= $comment['comment_text'] ?></div></article><?php endforeach; ?>
<?php if ($pages > 1): ?><nav class="pagination" aria-label="Comment pages"><?php if ($page > 1): ?><a href="?page=<?= $page - 1 ?>#comments">Newer comments</a><?php endif; ?><?php if ($page < $pages): ?><a href="?page=<?= $page + 1 ?>#comments">Older comments</a><?php endif; ?></nav><?php endif; ?>
</section>
<?php page_end(); ?>
