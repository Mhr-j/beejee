<?
header( 'Content-Type: text/html; charset=utf-8' );
date_default_timezone_set("Europe/Moscow");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_reporting', E_ALL);
session_start();?>
<html>
	<head>
		<title>Авторизация</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head> 
	<body> 
		<?
		include '../db_connect.php';
		include 'controller.php';
		include 'model.php';
		include 'view.php';
		?>
	</body>
</html>