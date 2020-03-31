<?
if(!isset($_SESSION['message'])){
	$_SESSION['message'] = '';
}
if(isset($_REQUEST['auth'])){
	$error = false;
	if (empty($_REQUEST['login'])){
		$_SESSION['message'] .= '! Не введён логин.<br>';
		$error = true;
	}
	if (empty($_REQUEST['pass'])){
		$_SESSION['message'] .= '! Не введён пароль.<br>';
		$error = true;
	}
	if($error == false){
		$stmt = $db->prepare('SELECT * FROM bejee_users WHERE login = ? AND pass = ?');
		$sql_array = array(htmlspecialchars($_REQUEST['login']), htmlspecialchars($_REQUEST['pass']));
		$stmt->execute($sql_array);
		$arrTasks = $stmt->fetchAll();
		if(count($arrTasks) == 1){
			$_SESSION['request'] = $_REQUEST;
			$_SESSION['admin_id'] = $arrTasks[0]['id'];
			$_SESSION['admin_login'] = $arrTasks[0]['login'];
			$_SESSION['auth'] = 'Вы авторизованы как <b>'.$arrTasks[0]['login'].'</b> <a href="/beejee/auth/?logout=Y">Выйти</a>';
			$_SESSION['message'] .= 'Вы успешно авторизовались.<br>';
			header('Location: /beejee/tasks/');
			die();
		}else{
			$_SESSION['message'] .= '! Не найден пользователь с такими логином и паролем.<br>';
			$_SESSION['request'] = $_REQUEST;
			header('Location: ');
			die();
		}
	}
}
if(isset($_REQUEST['logout'])){
	unset($_SESSION['admin_id']);
	unset($_SESSION['admin_login']);
	unset($_SESSION['auth']);
	$_SESSION['message'] .= 'Вы вышли из аккаунта.<br>';
	$parts = explode('?', $_SERVER['REQUEST_URI']);
	header('Location: /beejee/tasks/');
	die();
}
?>