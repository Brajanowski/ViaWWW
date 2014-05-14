<?php
if ($user->isLoggedIn()) {
	switch (Input::get("action")) {
	case 'force':
		$cost = $user->stats()->force * $user->stats()->force;

		if ($user->stats()->money >= $cost) {
			$uMoney = $user->stats()->money - $cost;
			$uStat = $user->stats()->force + 1;
			DB::getInstance()->query("UPDATE stats SET money=?, `force`=? WHERE user_id=?", array($uMoney, $uStat, $user->data()->id));
		}

		Redirect::to("index.php?site=training");
		break;

	case 'intellect':
		$cost = $user->stats()->intellect * $user->stats()->intellect;

		if ($user->stats()->money >= $cost) {
			$uMoney = $user->stats()->money - $cost;
			$uStat = $user->stats()->intellect + 1;
			DB::getInstance()->query("UPDATE stats SET money=?, `intellect`=? WHERE user_id=?", array($uMoney, $uStat, $user->data()->id));
		}

		Redirect::to("index.php?site=training");
		break;

	case 'mobility':
		$cost = $user->stats()->mobility * $user->stats()->mobility;

		if ($user->stats()->money >= $cost) {
			$uMoney = $user->stats()->money - $cost;
			$uStat = $user->stats()->mobility + 1;
			DB::getInstance()->query("UPDATE stats SET money=?, `mobility`=? WHERE user_id=?", array($uMoney, $uStat, $user->data()->id));
		}

		Redirect::to("index.php?site=training");
		break;

	default:
		$force_cost = $user->stats()->force * $user->stats()->force;
		$mobility_cost = $user->stats()->mobility * $user->stats()->mobility;
		$intellect_cost = $user->stats()->intellect * $user->stats()->intellect;
		echo "Force: ".$user->stats()->force." <a href='?site=training&action=force'>Go to training - ".$force_cost."$</a><br>";
		echo "Mobility: ".$user->stats()->mobility." <a href='?site=training&action=mobility'>Go to training - ".$mobility_cost."$</a><br>";
		echo "Intellect: ".$user->stats()->intellect." <a href='?site=training&action=intellect'>Go to training - ".$intellect_cost."$</a><br>";
		break;
	}
}