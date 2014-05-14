<?php
require_once "core/init.php";
$user = new User();

if ($user->isLoggedIn()) {	
	// Update level:

	if ($user->stats()->exp >= $user->stats()->exp_to_level) {
		$exp_ = $user->stats()->exp - $user->stats()->exp_to_level;
		$exp_to_level = $user->stats()->exp_to_level + $user->stats()->exp_to_level * $user->stats()->level;
		$level = $user->stats()->level + 1;

		DB::getInstance()->query("UPDATE stats SET exp=?, exp_to_level=?, level=? WHERE user_id=?", array($exp_, $exp_to_level, $level, $user->data()->id));
		Redirect::to("index.php");
	}
}

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
		<a href="?site=travel">Travel</a>
		<a href="?site=ranking">Ranking</a>
		<a href="?site=training">Training</a>
		<a href="?site=arena">Arena</a>
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

			case "travel":
				require_once "sites/travel.php";
				break;

			case "ranking":
				require_once "sites/ranking.php";
				break;

			case "profile":
				require_once "sites/profile.php";
				break;

			case "training":
				require_once "sites/training.php";
				break;

			case "arena":
				require_once "sites/arena.php";
				break;

			default:
				require_once "sites/home.php";
				break;

			}

			?>
		</div>

	</body>


</html>
