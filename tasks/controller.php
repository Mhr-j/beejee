<?
if(!isset($_SESSION['message'])){
	$_SESSION['message'] = '';
}

if(isset($_REQUEST['sort'])){
	if(!empty($_SESSION['sort']) && ($_SESSION['sort'] == $_REQUEST['sort'])){
		if($_SESSION['order'] == "ASC"){
			$_SESSION['order'] = "DESC";
		}elseif($_SESSION['order'] == "DESC"){
			$_SESSION['order'] = "ASC";
		}
	}elseif(in_array($_REQUEST['sort'], array('user_name', 'email', 'task_text', 'done'))){
		$_SESSION['sort'] = $_REQUEST['sort'];
		$_SESSION['order'] = "ASC";
	}
	unset($_GET['sort']);
	header('Location: ?'.http_build_query($_GET));
	die();
}
if(isset($_REQUEST['new_task'])){
	$error = false;
	if (empty($_REQUEST['user_name'])){
		$_SESSION['message'] .= '! Не введено имя пользователя.<br>';
		$error = true;
	}
	$email = $_REQUEST['email'] ?? '';
	if (filter_var($email, FILTER_VALIDATE_EMAIL) === false){
		$_SESSION['message'] .= '! Некорректный email.<br>';
		$error = true;
	}
	if (empty($_REQUEST['task_text'])){
		$_SESSION['message'] .= '! Не введён текст задачи.<br>';
		$error = true;
	}
	if($error == false){
		$sql_string = 'INSERT INTO bejee_tasks (user_name, email, task_text) VALUES (?, ?, ?)';
		$sql_array = array(htmlspecialchars($_REQUEST['user_name']), htmlspecialchars($_REQUEST['email']), htmlspecialchars($_REQUEST['task_text']));
		$stmt = $db->prepare($sql_string);
		if(!$stmt->execute($sql_array)){
			$_SESSION['message'] .= '! Ошибка базы данных: <br>'; 
			$_SESSION['message'] .= implode('<br>', $stmt->errorInfo());
			$_SESSION['message'] .= '<br>';
		}else{
			$_SESSION['message'] .= 'Задача добавлена.<br>';
		}
	}else{
		$_SESSION['request'] = $_REQUEST;
	}
	header('Location: ');
	die();
}elseif(isset($_REQUEST['edit']) && !empty($_SESSION['admin_id'])){
	$stmt = $db->prepare('SELECT user_name, task_text, done, edited FROM bejee_tasks WHERE id = ?');
	$stmt->execute(array($_REQUEST['edit']));
	$arrTasks = $stmt->fetchAll();
	if(count($arrTasks) == 1){
		$edit_form = '<details '.(isset($_REQUEST['edit']) ? 'open' : '').'>
			<summary><b>Редактирование задачи</b></summary>
			<form method="post">
				<fieldset>
					<div><b>Имя пользователя:</b> '.$arrTasks[0]['user_name'].'</div>
					<div><b>Текст задачи:</b><br><textarea name="task_text" placeholder="текст задачи">'.$arrTasks[0]['task_text'].'</textarea></div>
					<div>Выполнено <input type="checkbox" name="done" value="1" '.($arrTasks[0]['done'] == '1' ? 'checked' : '').'></div>
					<div>'.($arrTasks[0]['edited'] == 1 ? 'Отредактировано администратором' : '').'</div>
					<input type="hidden" name="task_id" value="'.$_REQUEST['edit'].'">
					<div><input type="submit" name="edit_task" value="Редактировать задачу"></div>
				</fieldset>
			</form>
		</details>';
	}
}
if(isset($_REQUEST['edit_task'])){
	if(!empty($_SESSION['admin_id'])){
		$stmt = $db->prepare('SELECT task_text, done, edited FROM bejee_tasks WHERE id = ?');
		$stmt->execute(array($_REQUEST['task_id']));
		$arrTasks = $stmt->fetchAll();

		if(count($arrTasks) == 1){
			$sql_string = 'UPDATE bejee_tasks SET ';
			$sql_array = array();
			$vals = array();
			if($arrTasks[0]['task_text'] != htmlspecialchars($_REQUEST['task_text'])){
				$edited = 1;
				$vals[] = 'task_text = ?';
				$vals[] = 'edited = ?';
				$sql_array[] = htmlspecialchars($_REQUEST['task_text']);
				$sql_array[] = 1;
			}
			if(($arrTasks[0]['done'] == 1 && empty($_REQUEST['done']))){
				$vals[] = 'done = ?';
				$sql_array[] = 0;
			}elseif($arrTasks[0]['done'] != 1 && !empty($_REQUEST['done']) && $_REQUEST['done'] == '1'){
				$vals[] = 'done = ?';
				$sql_array[] = 1;
			}
			if(count($vals) > 0){
				$sql_string .= implode(', ', $vals);
			}
			$sql_string .= 'WHERE id=?';
			$sql_array[] = $_REQUEST['task_id'];
			if(count($vals) > 0){
				$stmt = $db->prepare($sql_string);
				if($stmt->execute($sql_array)){
					$_SESSION['message'] .= 'Задача отредактирована.<br>';

				}else{
					$_SESSION['message'] .= '! Ошибка базы данных: <br>'; 
					$_SESSION['message'] .= implode('<br>', $stmt->errorInfo());
					$_SESSION['message'] .= '<br>'; 
				}
			}
		}
	}else{
		$_SESSION['message'] .= '! Недостаточно прав для редактирования задачи.<br>';
		$_SESSION['request'] = $_REQUEST;
	}
	header('Location: '.$_SERVER['REQUEST_URI']);
	die();
}
?>