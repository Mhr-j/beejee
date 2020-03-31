<?
$per_page = 3;
$sort = $_SESSION['sort'] ?? 'user_name';
$order = $_SESSION['order'] ?? 'ASC';

$sql = 'SELECT count(*) FROM bejee_tasks'; 
$stmt = $db->prepare($sql); 
$stmt->execute(); 
$items_all = $stmt->fetchColumn();
$pages_num = ceil($items_all / $per_page);

if(!empty($_REQUEST['page']) && is_numeric($_REQUEST['page'])){
	$begin = ($_REQUEST['page'] - 1) * 3;
	$cur_page = $_REQUEST['page'];
}else{
	$begin = 0;
	$cur_page = 1;
}

$stmt = $db->prepare('SELECT * FROM bejee_tasks ORDER BY '.$sort.' '.$order.' LIMIT '.$begin.', 3');
$stmt->execute(array());
$arrTasks = $stmt->fetchAll();
if(count($arrTasks) > 0){
	$TasksTable = '<h3>Список задач</h3><table border="1"><tr>
	<td><a href="?'.http_build_query(array_merge(['sort' => 'user_name'], $_GET)).'">Пользователь</a></td>
	<td><a href="?'.http_build_query(array_merge(['sort' => 'email'], $_GET)).'">Почта</a></td>
	<td><a href="?'.http_build_query(array_merge(['sort' => 'task_text'], $_GET)).'">Текст задачи</a></td>
	<td><a href="?'.http_build_query(array_merge(['sort' => 'done'], $_GET)).'">Статус</a></td>';
	if(!empty($_SESSION['admin_id'])){
		$TasksTable .= '<td>Редактировать</td>';
	}
	$TasksTable .= '</tr>';
	foreach($arrTasks as $k => $v){
		$TasksTable .= '<tr><td>'.$v['user_name'].'</td><td>'.$v['email'].'</td><td>'.$v['task_text'].'</td><td>'.($v['done'] == 1 ? 'выполнено' : 'в процессе').'</td>';
		if(!empty($_SESSION['admin_id'])){
			$TasksTable .= '<td><a href="?'.http_build_query(array_merge($_GET, ['edit' => $v['id']])).'">Редактировать</a></td>';
		}
		$TasksTable .= '</tr>';
	}
	$TasksTable .= '</table>';
}


$pagenavigation = '';
$page = 0;
while ($page++ < $pages_num): 
	if ($page == $cur_page){
		$pagenavigation .= '<b class="page">'.$page.'</b>';
	}else{
		$pagenavigation .= '<a class="page" href="?page='.$page.'">'.$page.'</a>';
	}
endwhile;

if(!empty($_SESSION['message'])){
	$MESSAGE = $_SESSION['message'];
	$_SESSION['message'] = '';
}
if(!empty($_SESSION['request'])){
	$request = $_SESSION['request'];
	$_SESSION['request'] = array();
}
?>