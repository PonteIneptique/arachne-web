<?php
	$app->get('/annotations/', function () use($app)  {
		$data = array();
		$data["annotations"] = Annotations::Available();
		display("./pages/annotations.all.php", $data, array("annotations"), $title = "Annotations");
	});
?>