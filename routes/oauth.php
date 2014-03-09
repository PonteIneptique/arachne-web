<?php
	#print "Description.php is here";
	$app->get('/oAuth/:provider', function ($provider) use ($app) {
		
		$GET = $app->request->get();
		$provider = strtolower($provider);

		if($provider == "twitter") {
			$data = User::oAuth1($app->request->get(), $provider, true);
		} else {
			$data = User::oAuth2($app->request->get(), $provider, true); 
		}

		if(isset($data["Location"])) {
			if($data["signin"] == true) {
				$d = $data["data"];
				$_SESSION["user"] = array("id" => $d["UID"], "name" => $d["Name"], "mail" => $d["Mail"]);
				$app->redirect($data["Location"]);
			} else {
				display("pages/login.php", array("status" => "error", "message" => $data["message"]));
			}
		} else {
			if(isset($data["Auth"])) {
				$app->redirect($data["Auth"]);
			} else {
				display("pages/login.php", array("status" => "error", "message" => "Unknown error."));
			}
		}
	} );
?>