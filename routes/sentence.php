<?php
	
	#DONE
	$app->get('/sentence/:id', function ($id) use($app)  {
		$data = array();
		$data["sentence"] = Sentence::Get($id);
		$data["forms"] = Sentence::Forms($id);
		$data["sentence"]["processed"] = Sentence::Process($data["sentence"], $data["forms"]);
		display("./pages/sentence.php", $data, $scripts = array("sentence"));
	});

?>