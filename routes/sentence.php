<?php
	
	#DONE
	$app->get('/sentence/:id', function ($id) use($app)  {
		$data = array();
		$data["sentence"] = Sentence::Get($id);
		$data["sentence"]["metadata"] = Sentence::Metadata($data["sentence"]["document"]);
		$data["forms"] = Sentence::Forms($id);
		$data["sentence"]["processed"] = Sentence::Process($data["sentence"], $data["forms"]);
		display("./pages/sentence.php", $data, $scripts = array("sentence"));
	});

	$app->get('/sentence/', function () use($app)  {
		$data = array();
		$data["sentences"] = Sentence::All();
		display("./pages/sentence.all.php", $data, $scripts = array("sentence"));
	});

?>