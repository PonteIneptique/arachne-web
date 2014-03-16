<?php
	$app->get('/lemma', function () use($app) {
		$data = array();
		$data["lemma"] = Lemma::All();

		display("./pages/lemma.all.php", $data, $scripts = array("lemma.all"), $title = "Lemma");
	});
	$app->get('/lemma/:lemmaId', function ($lemmaId) use($app) {
		$data = array();
		$data["lemma"] = Lemma::Get($lemmaId);
		$data["annotations"] = Annotations::Get("lemma", $lemmaId);
		$data["sentences"] = Lemma::Sentences($lemmaId);
		$data["forms"] = Lemma::All($lemmaId, true);

		display("./pages/lemma.php", $data, $scripts = array("sigma.min", "plugins/sigma.parsers.json.min", "plugins/sigma.layout.forceAtlas2.min", "plugins/sigma.plugins.neighborhoods.min", "lemma"), $title = "Lemma ".$data["lemma"]["lemma"]);
	});
?>