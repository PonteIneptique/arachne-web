<?php
	$app->get('/annotations/', function () use($app)  {
		$data = array();
		$data["annotations"] = Annotations::Available(false, true, false);
		if(isset($_SESSION["user"])) {
			$extrascripts = array("annotation-connected");
		} else {
			$extrascripts = array("annotations");
		}
		display("./pages/annotations.all.php", $data, $extrascripts, $title = "Annotations");
	});
?>