<?php

include("connection.class.php");
include("core.class.php");
Core::init();

class User {
	private $db;

	// Default variables for character
	private $hp = 24;
	private $hp_max = 24;
	private $force = 1;
	private $mobility = 1;
	private $intellect = 1;

	function __construct() {
		$this->db = Connection::getInstance();
	}

	public function getData() {
		$query = $this->db->prepare("select * from users where id=? limit 1");
		$query->bindParam(1, $_SESSION['user_id']);
		$query->execute();
		return $query->fetch();
	}

	public function login($user, $password) {
		$query = $this->db->prepare("select id from users where nick=? and password=? limit 1");
		$query->bindParam(1, $user);
		$password = md5($password);
		$query->bindParam(2, $password);
		$query->execute();

		$data = $query->fetch();

		$_SESSION['user_id'] = $data['id'];
	}

	public function isLoggedIn() {
		return isset($_SESSION['user_id']);
	}

	public function logout() {
		unset($_SESSION['user_id']);
	}

	public function isExists($nick) {
		$query = $this->db->prepare("select id from users where nick=? limit 1");
		$query->bindParam(1, $nick);
		$query->execute();

		$data = $query->fetchColumn();
		if ($data > 0) {
			return true;
		}
		else return false;
	}

	public function register($nick, $password, $password2) {
		if (empty($_SESSION['user_id'])) {
			if (strlen($nick) >= 3 && strlen($nick) < 24) {
				if (strlen($password) >= 5) {
					if ($password == $password2) {
						$user_exist = $this->isExists($nick);

						if (!$user_exist) {
							$query = $this->db->prepare("insert into users(nick, password) values(?, ?)");
							$query->bindParam(1, $nick);
							$password = md5($password);
							$query->bindParam(2, $password);
							$query->execute();
							echo "<br>Twoje konto zostało zarejestrowane, możesz teraz się zalogować<br>";
						}
						else echo "<br>Istnieje już taki użytkownik.<br>";
					}
					else echo "Podane hasła różnią się";
				}
				else echo "Hasło powinno zawierać minimum 5 znaków";
			}
			else echo "Nazwa użytkownika powinna zawierać od 3 do 24 znaków";
		}
		else echo "Jesteś zalogowany już na inne konto!";
	}



	public function addCharacter($name) {
		if ($this->isLoggedIn()) {

			$query = $this->db->prepare("select id from characters where name=? limit 1");
			$query->bindParam(1, $name);
			$query->execute();

			$data = $query->fetchColumn();
			$is_exist = false;
			if ($data > 0) {
				$is_exist = true;
			}

			if (!$is_exist) {
				$query = $this->db->prepare("insert into characters(nick, password) values(?, ?)");
				$query->bindParam(1, $name);
				$password = md5($password);
				$query->bindParam(2, $password);
				$query->execute();
			}
			else echo "Istenieje już taka postać";


		}
	}
};


?>