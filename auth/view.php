<div><?=$MESSAGE ?? ''?></div>
<?if(!empty($_SESSION['auth'])){?>
	<div><?=$_SESSION['auth']?></div>
<?}else{?>
<form method="post">
	<fieldset>
		<div><input required type="text" name="login" placeholder="логин" value="<?= htmlspecialchars($request['login'] ?? '') ?>"></div>
		<div><input required type="password" name="pass" placeholder="пароль" value="<?= htmlspecialchars($request['pass'] ?? '') ?>"></div>
		<div><input type="submit" name="auth" value="Авторизоваться"></div>
	</fieldset>
</form>
<?}?>