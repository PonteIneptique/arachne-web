<?php
	$app->get('/', function () use($app) {
		$metadata = Metadata::Get();
		
		$author = Author::Get();
		$lemma = Lemma::Weight(array("group" => false), "label");
		
		$i = 1;
		$data = array("nodes" => array());

		foreach($lemma as &$lem) {
			$data["nodes"][]  = array("name" => $lem["label"], "id" => $i);
			++$i;
		}
		$app->render("./sigma.php", array("metadata" => $metadata, "nodes" => $data["nodes"]));
	});

?>