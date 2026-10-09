<?php
function getDatabaseConnection(): mysqli {
    $servername = 'localhost';
    $username = 'admin';
    $password = 'admin';
    $dbName = 'testDB';
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = new mysqli($servername, $username, $password, $dbName);
    $conn->set_charset('utf8mb4');
    return $conn;
}
function db_query(string $sql, array $values = []): mysqli_stmt {
    global $conn;
    $statement = $conn->prepare($sql);
    if ($values) $statement->bind_param(str_repeat('s', count($values)), ...$values);
    $statement->execute();
    return $statement;
}

ini_set('display_errors', '1');
error_reporting(E_ALL);
session_name('simple_cat_blog');
session_start();
$conn = getDatabaseConnection();
$conn->query("CREATE TABLE IF NOT EXISTS UserAccounts (
    user_id INT(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_name VARCHAR(100) NOT NULL,
    user_email VARCHAR(150) NOT NULL,
    user_password VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
$columns = array_column($conn->query('SHOW COLUMNS FROM UserAccounts')->fetch_all(MYSQLI_ASSOC), 'Field');
if (!in_array('user_password', $columns, true)) {
    $conn->query("ALTER TABLE UserAccounts ADD COLUMN user_password VARCHAR(100) NOT NULL DEFAULT ''");
}
if (in_array('password_hash', $columns, true)) {
    $conn->query("UPDATE UserAccounts SET user_password = password_hash WHERE user_password = '' AND password_hash IS NOT NULL AND CHAR_LENGTH(password_hash) <= 100");
    $conn->query('ALTER TABLE UserAccounts DROP COLUMN password_hash');
}
$indexes = $conn->query('SHOW INDEX FROM UserAccounts')->fetch_all(MYSQLI_ASSOC);
if (!in_array('blog_unique_username', array_column($indexes, 'Key_name'), true)) {
    $conn->query('ALTER TABLE UserAccounts ADD UNIQUE INDEX blog_unique_username (user_name)');
}
foreach ([[1234, 'Tidus', 'tidus@example.com'], [2222, 'Yuna', 'yuna@example.com']] as $sample) {
    db_query('INSERT IGNORE INTO UserAccounts (user_id, user_name, user_email, user_password) VALUES (?, ?, ?, ?)', [$sample[0], $sample[1], $sample[2], '1234']);
    db_query("UPDATE UserAccounts SET user_password = '1234' WHERE user_id = ? AND user_password = ''", [$sample[0]]);
}
$conn->query("CREATE TABLE IF NOT EXISTS SimpleCatComments (
    comment_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    comment_text TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

function h($value): string { return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function posted(string $name): string { return isset($_POST[$name]) && is_string($_POST[$name]) ? $_POST[$name] : ''; }
function redirect(string $url): void { header('Location: ' . $url, true, 303); exit; }
function require_login(): array {
    if (!isset($_SESSION['user_id'])) redirect('login.php');
    $user = db_query('SELECT user_id, user_name, user_email FROM UserAccounts WHERE user_id = ?', [$_SESSION['user_id']])->get_result()->fetch_assoc();
    if (!$user) { unset($_SESSION['user_id']); redirect('login.php'); }
    return $user;
}
function password_for_hash(string $password): string { return base64_encode(hash('sha256', $password, true)); }
function valid_password(string $password, string $stored): bool {
    if ($stored === '') return false;
    // Hash written by this blog's registration form.
    if (strncmp($stored, 's256:', 5) === 0) return password_verify(password_for_hash($password), substr($stored, 5));
    // Plain bcrypt/argon2 hash.
    if (preg_match('/^\$(2[abxy]|argon2id?)\$/', $stored)) return strlen($password) <= 72 && password_verify($password, $stored);
    // Plain text password, e.g. the sample accounts created by update (1).php.
    return hash_equals($stored, $password);
}

function page_start(string $title): void {
    ?><!doctype html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= h($title) ?></title><style>
    *{box-sizing:border-box}body{margin:0;background:#f4f4f4;color:#222;font-family:Arial,sans-serif;line-height:1.5}main{max-width:900px;margin:30px auto;padding:0 18px}.card{background:white;border:1px solid #ddd;border-radius:8px;padding:24px;margin-bottom:20px}.login-card{max-width:420px;margin:50px auto}h1{font-size:26px;margin:0 0 18px}h2{font-size:20px;margin-top:0}a{color:#245ca0}label{display:block;margin:14px 0 5px}input,textarea{width:100%;padding:10px;border:1px solid #aaa;border-radius:4px;font:inherit}textarea{resize:vertical}button,.button{display:inline-block;padding:9px 16px;border:0;border-radius:4px;background:#245ca0;color:white;font:inherit;cursor:pointer;text-decoration:none}form>button{margin-top:15px}.header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;gap:12px}.header a{text-decoration:none}.profile-icon{display:flex;align-items:center;gap:7px;color:#222}.profile-icon span{display:grid;place-items:center;width:38px;height:38px;border:1px solid #aaa;border-radius:50%;font-size:22px;background:white}.cat-picture{display:block;width:100%;max-height:480px;object-fit:contain;background:#eee;border-radius:5px}.comments{width:72%;margin-left:auto}.comment{border-top:1px solid #ddd;padding:15px 0;overflow-wrap:anywhere}.comment p{margin:7px 0;white-space:pre-wrap}.comment img{display:block;max-width:100%;max-height:280px;object-fit:contain;margin-top:8px}.muted,small{color:#666}.error{color:#a21c1c;background:#fff0f0;padding:10px;border-radius:4px}.notice{background:#e9f5e9;padding:10px}.help{font-size:13px;overflow-wrap:anywhere}dl{display:grid;grid-template-columns:100px 1fr;gap:12px}dt{font-weight:bold}dd{margin:0;overflow-wrap:anywhere}.pagination{display:flex;justify-content:space-between;margin:15px 0}.actions{display:flex;gap:15px;align-items:center;flex-wrap:wrap}.actions form{margin:0}.actions form button{margin:0}a:focus-visible,button:focus-visible{outline:3px solid #dc8d19;outline-offset:3px}@media(max-width:600px){main{margin:18px auto}.card{padding:18px}.comments{width:100%}.login-card{margin:25px auto}dl{grid-template-columns:85px 1fr}}
    </style></head><body><main><?php
}
function page_end(): void { echo '</main></body></html>'; }

if (defined('CAT_BLOG_SHARED')) return;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && posted('action') === 'logout') {
    $_SESSION = [];
    $cookie = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $cookie['path']);
    session_destroy();
    redirect('login.php');
}
if (isset($_SESSION['user_id'])) { require_login(); redirect('index.php'); }
$register = ($_GET['register'] ?? '') === '1';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = posted('username'); $password = posted('password'); $email = posted('email');
    if ($register) {
        db_query('INSERT INTO UserAccounts (user_name, user_email, user_password) VALUES (?, ?, ?)', [$username, $email, 's256:' . password_hash(password_for_hash($password), PASSWORD_DEFAULT)]);
        $_SESSION['notice'] = 'Account created. You can now log in.';
        redirect('login.php');
    } else {
        $account = db_query('SELECT user_id, user_password FROM UserAccounts WHERE user_name = ?', [$username])->get_result()->fetch_assoc();
        if ($account && valid_password($password, $account['user_password'])) {
            $_SESSION['user_id'] = (int) $account['user_id'];
            redirect('index.php');
        }
        $error = 'Incorrect username or password.';
    }
}
page_start($register ? 'Register' : 'Login');
?>
<section class="card login-card">
<h1><?= $register ? 'Create an account' : 'Login' ?></h1>
<?php if (isset($_SESSION['notice'])): ?><p class="notice" role="status"><?= h($_SESSION['notice']) ?></p><?php unset($_SESSION['notice']); endif; ?>
<?php if ($error): ?><p class="error" role="alert"><?= h($error) ?></p><?php endif; ?>
<form method="post" action="login.php<?= $register ? '?register=1' : '' ?>">

<label for="username">Username</label><input id="username" name="username" autocomplete="username" value="<?= h(posted('username')) ?>">
<?php if ($register): ?><label for="email">Email address</label><input id="email" type="text" name="email" autocomplete="email" value="<?= h(posted('email')) ?>"><?php endif; ?>
<label for="password">Password</label><input id="password" type="password" name="password" autocomplete="<?= $register ? 'new-password' : 'current-password' ?>">
<?php if ($register): ?><p class="help muted">Use any letters, numbers, spaces or symbols. No password length or complexity rules.</p><?php endif; ?>
<button type="submit"><?= $register ? 'Register' : 'Login' ?></button>
</form>
<p><?= $register ? 'Already have an account? <a href="login.php">Login</a>' : 'No account yet? <a href="login.php?register=1">Register</a>' ?></p>
</section>
<?php page_end(); ?>
