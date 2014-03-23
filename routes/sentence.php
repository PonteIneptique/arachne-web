<?php
	
	#DONE
	$app->get('/sentence/:id', function ($id) use($app)  {
		if(!is_numeric($id)) {
			if($id == "last") {
				$id = Logs::LastSentence();
				if($id === false ) {
					$id = Sentence::Random();
				}
			} else {
				$id = Sentence::Random();
			}
		}
		$data = array();
		$data["sentence"] = Sentence::Get($id);
		$data["sentence"]["metadata"] = Sentence::Metadata($data["sentence"]["document"]);
		$data["forms"] = Sentence::Forms($id);
		$data["sentence"]["processed"] = Sentence::Process($data["sentence"], $data["forms"]);
		$data["annotations"]["lemma"] =  Annotations::Available("lemma", $format = true);
		$data["annotations"]["sentence"] =  Annotations::Available("sentence", $format = true);
		$data["annotations"]["sentence-applied"] =  Annotations::Get("sentence", $id);


		if(isset($_SESSION["user"])) {

			$options = Logs::Related("id_sentence", $id);
			$options[]=  array("table" => "sentence", "target" => $id);
			$data["logs"] = Logs::Count($options, $_SESSION["user"]["id"]);

			$extrascripts = array("jquery.typeahead.bundle.min", "handlebars", "sentence-connected", "knob");
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