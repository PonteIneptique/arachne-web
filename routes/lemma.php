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

		$extrascripts = $scripts = array("sigma.min", "plugins/sigma.parsers.json.min", "plugins/sigma.layout.forceAtlas2.min", "plugins/sigma.plugins.neighborhoods.min", "lemma");

		if(isset($_SESSION["user"])) {

			$options = Logs::Related("id_lemma", $lemmaId);
			$options[]=  array("table" => "lemma", "target" => $lemmaId);
			$data["logs"] = Logs::Count($options, $_SESSION["user"]["id"]);

			$extrascripts[] = "lemma-connected";
			$extrascripts[] = "knob";
		} 

		display("./pages/lemma.php", $data, $extrascripts, $title = "Lemma ".$data["lemma"]["lemma"]);
	});
?>