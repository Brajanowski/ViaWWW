<?php 
if ($user->isLoggedIn()) {
	if ($user->stats()->action_type != 0 && $user->stats()->action_type != 2) {
		Redirect::to("index.php");
		exit();
	}

	switch (@Input::get("action")) {

	case "end":
		DB::getInstance()->query("UPDATE stats SET action_id=?, action_end=?, action_type=? WHERE user_id=?", array(0, 0, 0, $user->data()->id));
		Redirect::to("index.php?site=travel");
		break;

	case "travel":
		$travel = DB::getInstance()->query("SELECT * FROM travels WHERE id=?", array(Input::get("id")))->first();
		if ($user->stats()->level >= $travel->require_level) {
			if ($user->stats()->action_id <= 0) {
				$travel_end = time() + $travel->travel_time;
				DB::getInstance()->query("UPDATE stats SET action_id=?, action_end=?, action_type=? WHERE user_id=?", array($travel->id, $travel_end, 2, $user->data()->id));
			}
		}
		Redirect::to("index.php?site=travel");
		break;

	case 'getReward':
		$travel = DB::getInstance()->query("SELECT * FROM travels WHERE id=?", array($user->data()->id))->first();
		if (($user->stats()->action_end - time()) <= 0 && $user->stats()->action_id != -1) {
			$exp_update = $user->stats()->exp + round($travel->require_level * $travel->require_level * 1.5);

			DB::getInstance()->query("UPDATE stats SET action_id=?, action_end=?, exp=?, action_type=? WHERE user_id=?", array(0, 0, $exp_update, 0, $user->data()->id));
			Redirect::to("index.php?site=travel");
		}
		else {
			Redirect::to("index.php");
		}
		break;


	default:
		if ($user->stats()->action_id <= 0) {
			$travel_list = DB::getInstance()->query("SELECT * FROM travels")->results();
			foreach($travel_list as $travel) {
				echo $user->stats()->level >= $travel->require_level ? "<div>" : "<div style='color: red;'>";

				if ($user->stats()->level >= $travel->require_level) {
					echo "<a href='?site=travel&action=travel&id=".$travel->id."'><b>".$travel->name."</b> Level: ".$travel->require_level."</a>";
				}
				else {
					echo "<b>".$travel->name."</b> Level: ".$travel->require_level;
				}
				echo "</div>";
			}	
		}
		else {
			$travel = DB::getInstance()->query("SELECT * FROM travels WHERE id=?", array($user->data()->id))->first();
			$time_to_end = $user->stats()->action_end - time();

			if (($user->stats()->action_end - time()) <= 0) {
				echo "<a href='?site=travel&action=getReward'>You back from travel! Get reward</a>";
			}
			else {
				echo "You travel to: ".$travel->name.", time to end: ".$time_to_end."<br>";
				echo "<a href='?site=travel&action=end'>End travel (You wont get a reward)</a>";				
			}
		}
		
		break;
	}
}
else {
	Redirect::to("index.php");
}