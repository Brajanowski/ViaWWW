<?php
if ($user->isLoggedIn()) {
	echo "Logged as ".$user->data()->username."<br>";

	echo "Force: ".$user->stats()->force."<br>";
	echo "Mobility: ".$user->stats()->mobility."<br>";
	echo "Intellect: ".$user->stats()->intellect."<br>";
	echo "Health: ".$user->stats()->hp."/".$user->stats()->hp_max."<br>";
	echo "Mana: ".$user->stats()->mana."/".$user->stats()->mana_max."<br>";
	echo "Energy: ".$user->stats()->energy."/".$user->stats()->energy_max."<br>";
	echo "Money: ".$user->stats()->money."<br>";
	echo "Level: ".$user->stats()->level."<br>";
	echo "Exp: ".$user->stats()->exp."/".$user->stats()->exp_to_level."<br><hr>";


}
else {
	echo "You are not logged in.";
}