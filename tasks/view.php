<div><?=$MESSAGE ?? ''?></div>
<div><?=$_SESSION['auth'] ?? '<a target="_blank" href="/beejee/auth/">Авторизоваться</a>'?></div>
<details <?if(isset($request['new_task'])) echo 'open';?>>
	<summary><b>Добавление задачи</b></summary>
	<form method="post">
		<fieldset>
			<div><input type="text" name="user_name" placeholder="имя пользователя" value="<?= htmlspecialchars($request['user_name'] ?? '') ?>"></div>
			<div><input required type="email" name="email" placeholder="email" value="<?= htmlspecialchars($request['email'] ?? '') ?>"></div>
			<div><textarea name="task_text" placeholder="текст задачи"><?= htmlspecialchars($request['task_text'] ?? '') ?></textarea></div>
			<div><input type="submit" name="new_task" value="Создать задачу"></div>
		</fieldset>
	</form>
</details>

<?
if(!empty($_GET['edit']) && !empty($_SESSION['admin_id'])){
	echo $edit_form;
}
?>
<?=$TasksTable ?? ''?>
<div><?=$pagenavigation ?? ''?></div>