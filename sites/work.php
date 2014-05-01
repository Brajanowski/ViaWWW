<?php
if ($user->isLoggedIn()) {
	switch (@Input::get("action")) {

	case "go":
		if ($user->stats()->work_id != -1) {
			Redirect::to("index.php");
			exit();
		}

		$work = DB::getInstance()->query("SELECT * FROM works WHERE id=? LIMIT 1", array(Input::get("work_id")));
		if ($work->count() < 1) {
			Redirect::to("index.php");
			exit();
		}

		$work = $work->first();
		if ($user->stats()->level < $work->required_level) {
			Redirect::to("index.php");
			exit();
		}

		$work_payment = $work->rate * Input::get("work_time");
		$work_end = time() + Input::get("work_time") * 60 * 60;
		$query = DB::getInstance()->query("UPDATE stats SET work_payment=?, work_id=?, work_end=? WHERE user_id=?",
										  					array($work_payment, Input::get("work_id"), $work_end, $user->data()->id));
		
		Redirect::to("index.php");
		break;

	case "end":
		$query = DB::getInstance("UPDATE stats SET work_id=? work_payment=?, work_end=? WHERE user_id=?", array(-1, 0, 0, $user->data()->id));
		echo $query->count();
		//Redirect::to("index.php");
		break;

	default:
		if ($user->stats()->work_id == -1) {
			$works = DB::getInstance()->query("SELECT * FROM works")->results();

			echo "<form method='post' action='?site=work&action=go'>";
			echo "<select name='work_time'>";

			for ($i = 1; $i < 13; ++$i) {
				echo "<option value='".$i."'>".$i." h</option>";
			}

			echo "</select>";

			$x = 0;
			foreach ($works as $work) {
				echo $user->stats()->level >= $work->required_level ? "<div>" : "<div style='color: red;'>";
				echo "<b>".$work->name."</b>";
				echo ", rate: ".$work->rate;
				echo ", required level: ".$work->required_level;
				if ($user->stats()->level >= $work->required_level)
					echo "<input type='radio' name='work_id' value=".$work->id.($x == 0 ? " checked" : "").">";
				echo "</div>";
				++$x;
			}

			echo "<input type='submit' value='Go to work!'>";
			echo "</form>";
		}
		else {
			$work = DB::getInstance()->query("SELECT * FROM works WHERE id=?", array($user->stats()->work_id))->first();
			$time_to_end = round(($user->stats()->work_end - time()) / 60 / 60);
			echo "You work as: <b>".$work->name."</b>, time to end: ".$time_to_end." h<br>";
			echo "<a href='?site=work&action=end'>End your work! (You will not get payment if you do this)</a>";
		}

		break;

	}
}
else {
	Redirect::to("index.php");
}
