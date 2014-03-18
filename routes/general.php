<?php
	$app->get('/', function () use($app)  {
		$data = array();
		
		if(isset($_SESSION["user"])) {
			$extrascripts = array();
		} else {
			$extrascripts = array();
		}

		display("./pages/home.php", $data, $extrascripts);
	});

	$app->notFound(function () use ($app) {
		display("./pages/home.php");
	});
?>