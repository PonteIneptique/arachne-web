<?php
	$app->get('/account/profile', function () {
		display("./pages/login.php", array());
	});
	$app->get('/account/login', function () {
		display("./pages/login.php", array(), array(), $title = "Login");
	});

	$app->get('/account/signout', function () use ($app) { 
		session_destroy();
		display("pages/home.php", array());
	} );
?>