<?php
	
	#DONE
	$app->get('/API/lemme/', function () use($app)  {

		$query = $app->request->get("query");
		$options = array("count" => false);
		if(strlen($query) > 0) {
			$options["query"] = $query;
		}
		$data = Lemma::Get($options);
		json($data);
	});

	#DONE
	$app->get('/API/lemme/:id/sentence', function ($id) use($app)  {
		$data = Sentences::Word($id);
		json($data);
	});
	
	#DONE without count
	$app->get('/API/lemme/item', function ()  use($app) {
		$data = Lemma::Get();
		json($data);
	});

	#Gets data about vote on a specific sentence and form
	$app->get('/API/sentence/:sentence/form/:form', function ($sentence, $form)  use($app) {
		$data = Sentence::Lemma($sentence, $form);
		json($data,$methods = "OPTIONS, GET");
	});

	#Get data for sigma
	$app->get('/API/Sigma', function () use($app) {
		$nodes = array();
		$nodesImport = Lemma::All();
		$range = Lemma::MaxMin();
		foreach($nodesImport as $index => $node) {
			$nodes[] = array("id" => $node["id_lemma"], "label" => $node["text_lemma"], "size" => Lemma::RelativeWeight($node["sentences"], $range["minimum"], $range["maximum"] ), "color" => Lemma::RelativeColor($node["sentences"], $range["minimum"], $range["maximum"] ), "x"=> rand(), "y" => rand());
		}

		$edges = array();
		$edgesImport = Lemma::Links();
		foreach ($edgesImport as $key => &$value) {
			$value["id"] = strval($key + 1);
			$edges[] = $value;
		}
		json(array("nodes"=>$nodes, "edges" => $edges),$methods = "OPTIONS, GET");
	});

	$app->get('/API/Sigma/:lemmaId', function ($lemmaId) use($app) {
		$nodes = array();
		$nodesImport = Lemma::All($lemma = $lemmaId);
		$range = Lemma::MaxMin($lemma = $lemmaId);
		foreach($nodesImport as $index => $node) {
			$nodes[] = array("id" => $node["id_lemma"], "label" => $node["text_lemma"], "size" => Lemma::RelativeWeight($node["sentences"], $range["minimum"], $range["maximum"] ), "color" => Lemma::RelativeColor($node["sentences"], $range["minimum"], $range["maximum"] ), "x"=> rand(), "y" => rand());
		}

		$edges = array();
		$edgesImport = Lemma::Links($lemma = $lemmaId);
		foreach ($edgesImport as $key => &$value) {
			$value["id"] = strval($key + 1);
			$edges[] = $value;
		}
		json(array("nodes"=>$nodes, "edges" => $edges), $methods = "OPTIONS, GET");
	});

	$app->post('/API/sentence/lemma', function () use($app)  {
		$data = array();
		$req = $app->request();
		$data["status"] = "success";
		$data["results"] = Sentence::NewForm($req->post("lemma"),$req->post("form"),$req->post("sentence"));
		if($data["results"] == false) { $data["status"] = "error"; }
		$data["parameters"] = array("lemma" => $req->post("lemma"),"sentence" => $req->post("sentence"),"form" => $req->post("form"));
		
		json($data, $methods = "OPTIONS, POST");
	});


?>