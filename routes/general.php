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
	$app->get('/about', function () use($app)  {
		$data = array();

		display("./pages/about.php", $data);
	});
	
	$app->get('/policies', function () use($app)  {
		$data = array();

		display("./pages/policies.php", $data);
	});

	$app->notFound(function () use ($app) {
		display("./pages/home.php");
	});
?>