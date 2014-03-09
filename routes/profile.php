<?php
	$app->get('/account/profile', function () {
		display("./pages/login.php", array());
	});
	$app->get('/account/login', function () {
		display("./pages/login.php", array(), array(), $title = "Login");
	});

	$app->get('/account/signout', function () use ($app) { 
		unset($_SESSION["user"]);
		session_destroy();
		display("pages/home.php", array());
	} );

	$app->post('/account/signup', function () use ($app) {
		$input = $app->request->post();
		$data = array();

		//First we checkup our data
		$requiredFields = array("mail" => "Email field is missing", "password" => "Password field is missing", "name" => "Name field is missing", "confirm" => "Confirm password field is missing");
		$data["error"] = array("signup" => array());
		foreach ($requiredFields as $field => $message) {
			if(!isset($input[$field]) || empty($input[$field]) || strlen($input[$field]) <= 3) {
				$data["error"]["signup"][$field] = $message;
			}
		}

		if(count($data["error"]["signup"]) >= 1) {

			display("pages/login.php", $data, array(), "Login");

		} elseif($input["confirm"] != $input["password"]) {

			$data["errors"]["signup"]["confirm"] = "Passwords are not equal";
			display("pages/login.php", $data, array(), "Login");

		} else {

			$data = User::signup($input);

			if(isset($data["status"]) && $data["status"] == "success") {
				display("pages/home.php", $data, array(), "Home");
			} else {
				display("pages/login.php", $data, array(), "Login");
			}
		}
	});
?>