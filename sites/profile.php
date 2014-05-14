<?php

$u = new User(Input::get("id"));

echo "Name: ".$u->data()->username."<br>";

echo "Force: ".$u->stats()->force."<br>";
echo "Mobility: ".$u->stats()->mobility."<br>";
echo "Intellect: ".$u->stats()->intellect."<br>";
echo "Health: ".$u->stats()->hp."/".$u->stats()->hp_max."<br>";
echo "Mana: ".$u->stats()->mana."/".$u->stats()->mana_max."<br>";
echo "Energy: ".$u->stats()->energy."/".$u->stats()->energy_max."<br>";
echo "Money: ".$u->stats()->money."<br>";
echo "Level: ".$u->stats()->level."<br>";
echo "Exp: ".$u->stats()->exp."/".$u->stats()->exp_to_level."<br>";
