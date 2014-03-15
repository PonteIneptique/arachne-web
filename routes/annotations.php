<?php
	$app->get('/annotations/', function () use($app)  {
		$data = array();
		$data["annotations"] = Annotations::Available(false, true, false);
		display("./pages/annotations.all.php", $data, array("annotations"), $title = "Annotations");
	});
?>