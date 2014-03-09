<?php
	$app->get('/', function () use($app)  {
		display("./pages/home.php");
	});

	$app->notFound(function () use ($app) {
		display("./pages/home.php");
	});
?>