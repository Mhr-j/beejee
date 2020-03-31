<?
if(!empty($_SESSION['message'])){
	$MESSAGE = $_SESSION['message'];
	$_SESSION['message'] = '';
}
if(!empty($_SESSION['request'])){
	$request = $_SESSION['request'];
	$_SESSION['request'] = array();
}
?>