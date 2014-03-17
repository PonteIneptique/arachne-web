<?php
	$app->get('/', function () use($app)  {
		$data = array();
		
		if(isset($_SESSION["user"])) {

			$options = array();
			$options[] = array("table" => "notuser", "target" => false);

			$data["logs"] = Logs::Count($options, $_SESSION["user"]["id"]);
			$extrascripts = array("home", "knob");
		} else {
			$extrascripts = array();
		}

		display("./pages/home.php", $data, $extrascripts);
	});

	$app->notFound(function () use ($app) {
		display("./pages/home.php");
	});
?>