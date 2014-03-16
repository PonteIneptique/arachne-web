<?php
	
	#DONE
	$app->get('/sentence/:id', function ($id) use($app)  {
		$data = array();
		$data["sentence"] = Sentence::Get($id);
		$data["sentence"]["metadata"] = Sentence::Metadata($data["sentence"]["document"]);
		$data["forms"] = Sentence::Forms($id);
		$data["sentence"]["processed"] = Sentence::Process($data["sentence"], $data["forms"]);
		$data["annotations"]["lemma"] =  Annotations::Available("lemma", $format = true);
		$data["annotations"]["sentence"] =  Annotations::Available("sentence", $format = true);
		$data["annotations"]["sentence-applied"] =  Annotations::Get("sentence", $id);

		if(isset($_SESSION["user"])) {
			$extrascripts = array("sentence-connected");
		} else {
			$extrascripts = array("sentence");
		}
		display("./pages/sentence.php", $data, $scripts = $extrascripts, $title = "Sentence " . $data["sentence"]["uid"]);
	});

	$app->get('/sentence/', function () use($app)  {
		$data = array();
		$data["sentences"] = Sentence::All();
		display("./pages/sentence.all.php", $data, $scripts = array("sentence.all"), $title = "Sentences");
	});

?>