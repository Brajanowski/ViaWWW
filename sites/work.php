<?php
if ($user->isLoggedIn()) {
	if ($user->stats()->action_type != 0 && $user->stats()->action_type != 1) {
		Redirect::to("index.php");
		exit();
	}
	switch (@Input::get("action")) {

	case "go":
		if ($user->stats()->action_id > 0) {
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
		$query = DB::getInstance()->query("UPDATE stats SET work_payment=?, action_id=?, action_end=?, action_type=? WHERE user_id=?",
										  					array($work_payment, Input::get("work_id"), $work_end, 1, $user->data()->id));
		
		Redirect::to("index.php?site=work");
		break;

	case "end":
		$query = DB::getInstance()->query("UPDATE stats SET action_id=?, work_payment=?, action_end=?, action_type=? WHERE user_id=?", array(0, 0, 0, 0, $user->data()->id));
		Redirect::to("index.php?site=work");
		break;

	case "getPayment":
		$work = DB::getInstance()->query("SELECT * FROM works WHERE id=?", array($user->stats()->work_id))->first();
		if (($user->stats()->action_end - time()) <= 0 && $user->stats()->action_id >= 0) {
			$updated_money = $user->stats()->work_payment + $user->stats()->money;
			$query = DB::getInstance()->query("UPDATE stats SET action_id=?, work_payment=?, action_end=?, action_type=?, money=? WHERE user_id=?", array(0, 0, 0, 0, $updated_money, $user->data()->id));
			Redirect::to("index.php?site=work");
		}
		else {
			Redirect::to("index.php");
		}

		break;

	default:
		if ($user->stats()->action_id <= 0) {
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
			$work = DB::getInstance()->query("SELECT * FROM works WHERE id=?", array($user->stats()->action_id))->first();

			if (($user->stats()->action_end - time()) <= 0) {
				echo "<a href='?site=work&action=getPayment'>You finished work! Get payment</a>";
			}
			else {
				$time_to_end = round(($user->stats()->action_end - time()) / 60 / 60);
				echo "You work as: <b>".$work->name."</b>, time to end: ".$time_to_end." h<br>";
				echo "<a href='?site=work&action=end'>End your work! (You will not get payment if you do this)</a>";	
			}
		}

		break;

	}
}
else {
	Redirect::to("index.php");
}
