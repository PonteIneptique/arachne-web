<?php
	$app->get('/lemma', function () use($app) {
		$data = array();
		$data["lemma"] = Lemma::All();

		display("./pages/lemma.all.php", $data);
	});
?>