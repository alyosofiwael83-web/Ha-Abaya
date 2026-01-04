<?php
$_GET['name'] = 'about';
ob_start();
include 'page.php';
file_put_contents('about.html', ob_get_clean());

$_GET['name'] = 'privacy';
ob_start();
include 'page.php';
file_put_contents('privacy.html', ob_get_clean());

$_GET['name'] = 'refund';
ob_start();
include 'page.php';
file_put_contents('refund.html', ob_get_clean());
?>
