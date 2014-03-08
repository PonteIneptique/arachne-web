<?php
	$app->get('/lemma', function () use($app) {
		$data = array();
		$data["lemma"] = Lemma::All();

		display("./pages/lemma.all.php", $data);
	});
	$app->get('/lemma/:lemmaId', function ($lemmaId) use($app) {
		$data = array();
		$data["lemma"] = Lemma::Get($lemmaId);
		$data["sentences"] = Lemma::Sentences($lemmaId);

		display("./pages/lemma.php", $data, $scripts = array("sigma.min", "plugins/sigma.parsers.json.min", "plugins/sigma.layout.forceAtlas2.min", "plugins/sigma.plugins.neighborhoods.min", "lemma"));
	});
?>