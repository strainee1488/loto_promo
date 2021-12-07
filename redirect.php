<?php
if (isset($_GET['stream_id']) && !empty($_GET['stream_id'])) $stream_id = $_GET['stream_id'];
else die('Error Site ID');

$token = "ubMk2gexdT8Sl5X3bhloNotkNdnRMjKlOVmgPuyV"; // Ваш API ключ
$url = base64_decode("aHR0cHM6Ly9jb252ZXJ0by5wbHVzL2FwaS9nZXRfc3RyZWFtX2xpbms=") . "/{$token}/?stream_id={$stream_id}";
$cache_file = md5($url) . '.json';
if (!file_exists($cache_file) || filemtime($cache_file) + 5 * 60 < time()) {
  $urldata = file_get_contents($url);
  if ($urldata) if (!file_put_contents($cache_file, $urldata)) die("File write error (create file '" . md5($url) . ".json' with 666 permission)");
}
$urldata = json_decode(file_get_contents($cache_file), 1);
if (isset($urldata['link'])) {
  $parts = parse_url($_SERVER['REQUEST_URI']);
  parse_str($parts['query'], $params);
  unset($params['stream_id']);
  header("Location: " . $urldata['link'] . "?" . http_build_query($params));
} else die('Error');