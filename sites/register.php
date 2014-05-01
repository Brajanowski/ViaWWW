<?php

if ($user->isLoggedIn()) {
	Session::flash('home', 'You are currently logged in!');
	Redirect::to('index.php');
}

if (Input::exists()) {
	if (Token::check(Input::get('token'))) {
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'username' => array(
				'required' => true,
				'min' => 2,
				'max' => 20,
				'unique' => 'users'
			),
			'password' => array(
				'required' => true,
				'min' => 6
			),
			'password_again' => array(
				'required' => true,
				'matches' => 'password'
			),
			'email' => array(
				'required' => true,
				'min' => 6,
				'max' => 256
			)
		));

		if ($validation->passed()) {
			$user = new User();

			$salt = Hash::salt(32);

			try {
				$user->create(array(
					'username' => Input::get('username'),
					'password' => Hash::make(Input::get('password'), $salt),
					'salt' => $salt,
					'joined' => date('Y-m-d H:i:s'),
					'email' => Input::get('email')
				));

				Session::flash('home', 'You have been registered and can now log in!');
				Redirect::to('index.php');
			} 
			catch(Exception $e) {
				die($e->getMessage());
			}
		}
		else {
			foreach ($validate->errors() as $error) {
				echo $error.'<br>';
			}
		}
	}
}

?>

<form action="" method="post">
	<div class="field">
		<label for="username">Username</label>
		<input type="text" name="username" id="username" value="<?php echo Input::get('username'); ?>" autocomplete="off">
	</div>

	<div class="field">
		<label for="password">Choose a password</label>
		<input type="password" name="password" id="password" value="" autocomplete="off">
	</div>

	<div class="field">
		<label for="password_again">Enter your password again</label>
		<input type="password" name="password_again" id="password_again" value="" autocomplete="off">
	</div>

	<div class="field">
		<label for="email">Enter your e-mail adress</label>
		<input type="text" name="email" id="email" value="" autocomplete="off">
	</div>

	<input type="hidden" name="token" value="<?php echo Token::generate();?>">
	<input type="submit" value="Register" />
</form>