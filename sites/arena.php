<?php
if ($user->isLoggedIn()) {

	switch(@Input::get("action")) {

	case 'findEnemy':
		$u = DB::getInstance()->query("SELECT user_id FROM stats WHERE user_id !=? AND level>? AND level<? AND user_id!=? LIMIT 1",
			array($user->data()->id, $user->stats()->level - 2, $user->stats()->level + 2, $user->stats()->lastFight))->first();
		
		Session::put("fight_id", $u->user_id);
		Redirect::to("index.php?site=arena&action=fight");
		break;

	case 'fight':
		if (Session::exists("fight_id")) {
			$u = new User(Session::get("fight_id"));
			//Session::delete("fight_id");
			$current_our_hp = $user->stats()->hp;
			$current_enemy_hp = $u->stats()->hp;

			for ($x = 0;;) {
				if ($current_enemy_hp <= 0 || $current_our_hp <= 0) {
					break;
				}

				if ($x == 0) {
					$force = $user->stats()->force * rand(1, $user->stats()->level + 1);

					echo "<div>";
					echo "<h4>".$user->data()->username."(".$current_our_hp.") attack with power of ".$force."</h4>";
					echo "<h4>".$u->data()->username."(".$current_enemy_hp.") got ".$force." damage</h4>";
					echo "</div>";

					$current_enemy_hp -= $force;
					$x = 1;
				}
				else if ($x == 1) {
					$force = $u->stats()->force * rand(1, $u->stats()->level + 1);

					echo "<div>";
					echo "<h4>".$u->data()->username."(".$current_enemy_hp.") attack with power of ".$force."</h4>";
					echo "<h4>".$user->data()->username."(".$current_our_hp.") got ".$force." damage</h4>";
					echo "</div>";

					$current_our_hp -= $force;
					$x = 0;
				}
			}
		}
		break;

	default:
		echo "<a href='?site=arena&action=findEnemy'>Go fight</a>";
		break;
	}

}