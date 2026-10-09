<?php
#define directory containing files to view
$directory = __DIR__ . DIRECTORY_SEPARATOR . 'files';
#get URL parameter 'file'
$file = $_GET['file'] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>This is a file viewer!</title>
</head>
<body>

<h1>File Viewer</h1>
<ul>
<?php

#scan directory for files to view
$files = array_diff(scandir($directory), array('.','..'));

#print list of files to view
foreach ($files as $filename) {
  $filepath = $directory . DIRECTORY_SEPARATOR . $filename;

  if (is_file($filepath)) {
	  echo '<li>';
	  echo '<a href="?file=' . urlencode($filename) . '">';
	  echo htmlspecialchars($filename, ENT_QUOTES, 'UTF-8');
	  echo '</a>';
	  echo '</li>';
  }
}
?>
</ul>
<?php
if ($file !== null) {
  #basename() gets only the file name, stripping any ../, remove comment to mitigate
  $file = basename($file);

  #display contents of file being viewed
  $filepath = $directory . DIRECTORY_SEPARATOR . $file;
  $contents = file_get_contents($filepath);
  echo '<pre>';
  echo htmlspecialchars($contents, ENT_QUOTES, 'UTF-8');
  echo '</pre>';
}
?>

</body>
</html>
