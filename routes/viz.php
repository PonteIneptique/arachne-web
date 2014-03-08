<?php
	$app->get('/viz', function () use($app) {
		$scripts = array("sigma.min", "plugins/sigma.parsers.json.min", "plugins/sigma.layout.forceAtlas2.min", "plugins/sigma.plugins.neighborhoods.min", "viz");
		display("./pages/sigma.php", array(), $scripts, $title = "Visualisation");
	});

?>