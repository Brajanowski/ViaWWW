<?php
if (!$user->isLoggedIn()) {
	Session::flash('home', 'You are not logged in');
	Redirect::to('index.php');
}

$user->logout();

Session::flash('home', 'Logged out!');
Redirect::to('index.php');