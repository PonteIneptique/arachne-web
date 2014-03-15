<?php



	/*
	*
	*
	*		Votes
	*
	*/
	
	$app->post('/API/vote/forms', function () use($app)  {

		if(!isset($_SESSION["user"])) {
			return status("error");
		}

		$req = $app->request();

		//Getting the id of the link
		$id_lemma_has_form = Forms::LemmaHasForm($req->post("lemma"),$req->post("form"),$req->post("sentence"));

		if($id_lemma_has_form == false) {
			//Exit if we dont retrieve anything
			return status("error", "POST, OPTIONS");
		} else {
			//Inserting vote
			$status = Forms::Vote($id_lemma_has_form, $req->post("value", $_SESSION["user"]["id"]));

			//Returning values
			if($status == true) {
				return status("success", "POST, OPTIONS");
			} else {
				return status("error", "POST, OPTIONS");
			}
			json($data, $methods = "POST, OPTIONS");
		}
	});
	$app->post("/API/vote/annotations/:target", function($target) use($app) {
		$vote = $app->request->post("vote");

		if(!isset($_SESSION["user"]) || $vote === "") {
			return status("error");
		}
		$data = Annotations::Vote($target, $_SESSION["user"]["id"], $vote);
		if($data) {
			return status("success");
		}
		return status("error");
	});


	/*
	*
	*
	*		Annotations
	*
	*/

	$app->post("/API/annotations/value/:type", function($type) use($app) {
		$name = $app->request->post("name");

		if(!isset($_SESSION["user"]) || $type == "" || $name == "") {
			return status("error");
		}
		$data = Annotations::ValueNew($name, $type, $_SESSION["user"]["id"]);
		if($data != false) {
			json(array("status" => "success", "id" => $data), "POST, OPTIONS");
			return true;
		}
		return status("error");
	});


	$app->post("/API/annotations/type", function() use($app) {
		$type = $app->request->post("type_name");
		$target = $app->request->post("target");

		if(!isset($_SESSION["user"]) || $type == "" || ($target != "sentence" && $target != "lemma")) {
			return status("error");
		}
		$data = Annotations::TypeNew($type, $target, $_SESSION["user"]["id"]);
		if($data != false) {
			json(array("status" => "success", "id" => $data), "POST, OPTIONS");
			return true;
		}
		return status("error");
	});

	$app->post("/API/annotations/:table/:id", function($table, $lemma) use($app) {
		$type = $app->request->post("type");
		$value = $app->request->post("value");

		if(!isset($_SESSION["user"]) || $type == "" || $value == "" || ($table != "sentence" && $table != "lemma")) {
			return status("error");
		}
		$data = Annotations::Insert($table, $lemma, $type, $value, $_SESSION["user"]["id"]);
		if($data) {
			return status("success");
		}
		return status("error");
	});
	
	$app->get('/API/annotations/:table/:id', function ($table, $id) use($app)  {
		$data = Annotations::Get($table, $id);
		json($data, $methods = "GET, POST, OPTIONS");
	});

	$app->get('/API/annotations/list', function () use($app)  {
		$data = Annotations::Available($format = true);
		json($data, $methods = "GET, OPTIONS");
	});

	$app->get('/API/annotations/list/:target', function ($target) use($app)  {
		$data = Annotations::Available($target, $format = true);

		json($data, $methods = "GET, OPTIONS");
	});


	/*
	*
	*
	*		Lemma
	*
	*/

	$app->post('/API/lemma/', function () use($app)  {

		$query = $app->request->post("query");
		$options = array("count" => false);
		if(strlen($query) > 0) {
			$options["query"] = $query;
		}
		$data = Lemma::Get($options);
		json($data, $methods = "POST, GET, OPTIONS");
	});


	/*
	*
	*
	*	Sentence
	*
	*/

	#Gets data about vote on a specific sentence and form
	$app->get('/API/sentence/:sentence/form/:form', function ($sentence, $form)  use($app) {
		$data = Sentence::Lemma($sentence, $form);

		foreach($data as $key => &$value) {
			$value["annotations"] = Annotations::Get("lemma", $value["id_lemma"]);
			$value["context"] = Annotations::Get("lemma_has_form",  $value["id_lemma_has_form"]);
		}
		json($data,$methods = "OPTIONS, GET");
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


	/*
	*
	*
	*	Visualisation
	*
	*/

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

?>