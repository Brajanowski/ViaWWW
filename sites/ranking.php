<?php

echo "<ol>";

$list = DB::getInstance()->query("SELECT * FROM stats ORDER BY id ASC")->results();

foreach($list as $l) {
	echo "<li>";

	$u = new User($l->user_id);

	echo "<a href='?site=profile&id=".$u->data()->id."'>".$u->data()->username."(".$u->stats()->level.")</a>";

	echo "</li>";
}

echo "</ol>";
