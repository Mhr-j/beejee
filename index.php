<?
header( 'Content-Type: text/html; charset=utf-8' );
date_default_timezone_set("Europe/Moscow");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_reporting', E_ALL);?>
<html>
	<head>
		<title>Менеджер задач</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
		<style>
		table{
			border-collapse: collapse;
		}
		table td{
			padding:5px;
		}
		.page{
			margin: 5px;
		}
		</style>
	</head> 
	<body> 
		<?
		include '../db_connect.php';
		include 'controller.php';
		include 'model.php';
		include 'view.php';
		unset($_SESSION['request']);?>
	</body>
</html>