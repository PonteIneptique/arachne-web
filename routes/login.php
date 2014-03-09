<?php
	$app->map('/login/', function () use ($app, $require) {
		// Don't forget to set the correct attributes in your form (name="user" + name="password")
		$require->req(array("user", "log"));
		if(count($app->request->post()) > 0) {
			$input = $app->request->post();
		} elseif(count($app->request()->getBody()) > 0) {
			$input = $app->request()->getBody();
		} else {
			$app->response()->status(400);
		}
		
		if(isset($input["user"]) && isset($input["password"]))
		{
			$data = User::login($input);
			
			if($data["signin"] == true) {
				$d = $data["data"];
				$app->setEncryptedCookie('t23-p', hash("sha256", $input["password"]), "2 days");
				$app->setEncryptedCookie('t23-u',$input["user"], "2 days");
				$app->setEncryptedCookie('t23-i',$d["UID"], "2 days");
				$app->setCookie('logged',$d["Name"], "20 minutes", null,  COOKIE_DOMAIN);
				
				$_SESSION["user"] = array("id" => $d["UID"], "name" => $d["Name"], "mail" => $d["Mail"]);
				
				return jP($d);
			} else {
				return jP($data);
			}
			
		}
		else
		{
			$app->response()->status(401);
		}
	})->via('POST')->name('login');
	
	$app->map('/signup/', function () use ($app, $require) {
		$require->req(array("user", "log"));
		// Don't forget to set the correct attributes in your form (name="user" + name="password")
		
		if(count($app->request->post()) > 0) {
			$input = $app->request->post();
		} elseif(count($app->request()->getBody()) > 0) {
			$input = $app->request()->getBody();
		} else {
			$app->response()->status(400);
		}
		
		if(isset($input["mail"]) && isset($input["password"]) && isset($input["name"]) && isset($input["user"]))
		{
			$data = User::signup($input);
			
			if(isset($data["Success"])) {
				return jP($data);
			} else {
				return jP($data);
			}
		}
		else
		{
			$app->response()->status(401);
		}
	})->via('POST')->name('signup');
	
	$app->get('/account/signout', function () use ($app) { 
		session_destroy();
		display("home.php", array());
	} );
?>
