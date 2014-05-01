<?php
require_once "core/init.php";
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
		if ($user->isLoggedIn()) {
		?>
		<a href="index.php">Home</a>
		<a href="?site=work">Work</a>
		<a href="?site=logout">Log out</a>

		<?php
		}
		else {
		?>

		<a href="?site=login">Log in</a>
		<a href="?site=register">Register</a>

		<?php
		}
		?>
		</div>

		<div id="content">
			<?php

			switch (@Input::get("site")) {

			case "login":
				require_once "sites/login.php";
				break;

			case "register":
				require_once "sites/register.php";
				break;

			case "logout":
				require_once "sites/logout.php";
				break;

			case "work":
				require_once "sites/work.php";
				break;

			default:
				require_once "sites/home.php";
				break;

			}

			?>
		</div>

	</body>


</html>
