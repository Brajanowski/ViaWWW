
<?php
include("classes/user.class.php");
$user = new User();

?>

<!doctype html>
<html>

	<head>
		
		<meta charset="UTF-8">
		<title> Via www </title>
		<link rel="Stylesheet" type="text/css" href="css/style.css" />

	</head>

	<body>
		<div id="menu">
			<?php
if (!$user->isLoggedIn()) {
			?>
			<a href="index.php">Strona główna</a> |
			<a href="index.php?site=login">Zaloguj się</a> |
			<a href="index.php?site=register">Zarejestruj się</a>

			<?php
			}
			else {
			?>
			<a href="index.php">Strona główna</a> |
			<a href="index.php?site=character">Postać</a> |

			<a href="index.php?site=logout">Wyloguj się</a>

			<?php
			}
			?>
		</div>

		<div id="content">
			<?php

			switch (@$_GET['site']) {

			case 'login':

			if (!$user->isLoggedIn()) {
			?>

			<center>
				<form method="post">
					<input name="login" type="text" placeholder="Nick" /><br>
					<input name="password" type="password" placeholder="Hasło" /><br>
					<input name="submit" type="submit" value="Zaloguj się!" /><br>
				</form>
			</center>

			<?php

				if (!empty($_POST['submit'])) {
					$user->login($_POST['login'], $_POST['password']);
				}
			}
			else {
				echo "JESTES ZALOGOWANY jako ".$user->getData()['nick']."<br>sesja: ".$_SESSION['user_id'];
			}

				break;

			case 'register':

			if (!$user->isLoggedIn()) {
			?>
			<center>
				<form method="post">
					<input name="login" type="text" placeholder="Nick" /><br>
					<input name="password" type="password" placeholder="Hasło" /><br>
					<input name="password2" type="password" placeholder="Powtórz hasło" /><br>
					<input name="submit" type="submit" value="Zarejestruj się!" /><br>
				</form>
			</center>
			<?php
				if (!empty($_POST['submit'])) {
					$user->register($_POST['login'], $_POST['password'], $_POST['password2']);
				}
			}
			else {
				echo "JESTES ZALOGOWANY jako ".$user->getData()['nick']."<br>sesja: ".$_SESSION['user_id'];
			}

				break;
			
			case 'logout':
				$user->logout();
				break;

			default:

				break;
			}

			?>
		</div>

	</body>


</html>
