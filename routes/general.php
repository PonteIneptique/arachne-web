<?php
	$app->get('/', function () use($app)  {
		display("./pages/home.php");
	});
?>