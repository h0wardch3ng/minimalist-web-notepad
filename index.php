<?php

// Base URL of the website, without trailing slash.
//$base_url = 'https://notes.orga.cat';

// relative path
$base_url = '';

// Disable caching.
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// If a note's name is not provided or contains invalid characters.
if (!isset($_GET['note']) || !preg_match('/^[a-zA-Z0-9_-]+$/', $_GET['note'])) {

    // Generate a name with 5 random unambiguous characters. Redirect to it.
    header("Location: $base_url/" . substr(str_shuffle('234579abcdefghjkmnpqrstwxyz'), -5));
    die;
}

$path = '_tmp/' . $_GET['note'];

if (isset($_POST['text'])) {
    if (isset($_REQUEST["mode"]) && $_REQUEST["mode"] == "append") {
        file_put_contents($path, $_POST['text'], LOCK_EX | FILE_APPEND);
        die();
    }

    // Update file.
    file_put_contents($path, $_POST['text'], LOCK_EX);

    // If provided input is empty, delete file.
    if (!strlen($_POST['text'])) {
        unlink($path);
    }
    die;
}

// Output raw file if client is curl.
if (strpos($_SERVER['HTTP_USER_AGENT'], 'curl') === 0) {
    if (is_file($path)) {
        print file_get_contents($path);
    }
    die;
}
// text mode
if (isset($_GET["mode"])) {
    if (is_file($path)) {
        if (isset($_GET["type"])) {
            switch($_GET["type"]) {
                case "html":
                    header('Content-type: text/html');
                    break;
                case "css":
                    header('Content-type: text/css');
                    break;
                case "js":
                    header('Content-type: text/javascript');
                    break;
                case "json":
                    header('Content-type: application/json');
                    break;
                default:
                    header('Content-type: text/txt');
            }
        } else {
            header('Content-type: text/plain');
        }

        switch($_GET["mode"]) {
            case "base64":
                print base64_encode(file_get_contents($path));
                break;
            case "plain":
                print file_get_contents($path);
                break;
            case "md5":
                print hash_file('md5', $path);
                break;
            case "mtime":
                print filemtime($path);
                break;
            default:
                print file_get_contents($path);
                break;
        }
    }
    die;
}
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="generator" content="Minimalist Web Notepad (https://github.com/pereorga/minimalist-web-notepad)">
    <title><?php print $_GET['note']; ?></title>
    <link rel="shortcut icon" href="<?php print $base_url; ?>/favicon.ico">
    <link rel="stylesheet" href="<?php print $base_url; ?>/styles.css">
</head>
<body>
    <div class="nav">
        <a href="<?php print $_GET['note']; ?>?mode=plain">Plain</a>
        <a href="<?php print $_GET['note']; ?>?mode=base64">Base64</a>
        <a href="<?php print $_GET['note']; ?>?mode=md5">MD5</a>
        <a href="<?php print $_GET['note']; ?>?mode=mtime">Mtime</a>
        <a href="<?php print $_GET['note']; ?>?mode=plain&type=html">Type:HTML</a>
        <a href="<?php print $_GET['note']; ?>?mode=plain&type=css">Type:CSS</a>
        <a href="<?php print $_GET['note']; ?>?mode=plain&type=javascript">Type:JS</a>
    </div>
    <div class="container">
        <textarea id="content"><?php
            if (is_file($path)) {
                print htmlspecialchars(file_get_contents($path), ENT_QUOTES, 'UTF-8');
            }
        ?></textarea>
    </div>
    <pre id="printable"></pre>
    <script src="<?php print $base_url; ?>/script.js"></script>
</body>
</html>
