<?php

	/*
	*
	*
	*		Relationships && Polarity
	*
	*/

	$app->post("/API/relationship", function() use ($app) {

		if(!isset($_SESSION["user"])) {
			return status("error");
		}

		$req = $app->request();

		if(!is_numeric($req->post("lemma"))) {
			return status("error");
		}
		if(!is_numeric($req->post("val"))) {
			return status("error");
		}
		if(!is_numeric($req->post("form"))) {
			return status("error");
		}
		if(!is_numeric($req->post("sentence"))) {
			return status("error");
		}

		$data = Relationship::Insert($_SESSION["user"]["id"], $req->post("form"), $req->post("lemma"), $req->post("sentence"), $req->post("val"));
		if($data == true) {
			status("success", $methods = "POST, OPTIONS");
		} else {
			status("error", $methods = "POST, OPTIONS");
		}
	});

	$app->post("/API/polarity", function() use ($app) {

		if(!isset($_SESSION["user"])) {
			return status("error");
		}

		$req = $app->request();

		if(!is_numeric($req->post("lemma"))) {
			return status("error");
		}
		if(!is_numeric($req->post("val"))) {
			return status("error");
		}

		$data = Polarity::Insert($_SESSION["user"]["id"], $req->post("lemma"), $req->post("val"));
		if($data == true) {
			status("success", $methods = "POST, OPTIONS");
		} else {
			status("error", $methods = "POST, OPTIONS");
		}
	});

	/*
	*
	*
	*	Logs TEST
	*
	*
	*/
/*

	$app->get("/API/logs/user", function() use ($app) {

		$options = array();
		$options[] = array("table" => "notuser", "target" => false);

		$data = Logs::Count($options, $_SESSION["user"]["id"]);
		json($data, $methods = "POST, OPTIONS");
	});

	$app->get("/API/logs/sentence/:idSentence", function($idSentence) use ($app) {

		$options = Logs::Related("id_sentence", $idSentence);
		$options[]=  array("table" => "sentence", "target" => $idSentence);

		$data = Logs::Count($options, $_SESSION["user"]["id"]);
		json($data, $methods = "POST, OPTIONS");
	});
*/

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
		$data = Lemma::Get(false, $options);
		json($data, $methods = "POST, GET, OPTIONS");
	});

	$app->get('/API/lemma/', function () use($app)  {

		$query = $app->request->get("query");
		$options = array("count" => false, "list" => true);
		if(strlen($query) > 0) {
			$options["query"] = $query;
		}
		$data = Lemma::Get(false, $options);
		json($data, $methods = "POST, GET, OPTIONS");
	});


	/*
	*
	*
	*	Sentence
	*
	*/

	#Gets data about vote, annotations and stuff on a specific sentence and form
	$app->get('/API/sentence/:sentence/form/:form', function ($sentence, $form)  use($app) {
		$data = Sentence::Lemma($sentence, $form);

		foreach($data as $key => &$value) {
			$value["annotations"] = Annotations::Get("lemma", $value["id_lemma"]);
			$value["context"] = Annotations::Get("lemma_has_form",  $value["id_lemma_has_form"]);
			if(isset($_SESSION["user"])) {
				$value["polarity"] = Polarity::Get($_SESSION["user"]["id"], $value["id_lemma"]);
			}
		}
		$data = array("lemma" => $data);
		if(isset($_SESSION["user"])) {
			$data["relationships"] = Relationship::Get($sentence, $form);
		}

		json($data,$methods = "OPTIONS, GET");
	});


	$app->post('/API/sentence/lemma', function () use($app)  {
		$data = array();

		//Getting request params
		$req = $app->request();
		$lemma = $req->post("lemma");
		$form = $req->post("form");
		$sentence = $req->post("sentence");

		//Checking them
		if(!isset($_SESSION["user"]) || $lemma === "" || $form === "" || $sentence === "") {
			return status("error");
		}
		$data["status"] = "success";
		$data["results"] = Sentence::NewForm($lemma, $form, $sentence);

		if($data["results"] == false) { 
			return status("error");
		}
		$data["parameters"] = array("lemma" => $lemma,"sentence" => $sentence,"form" => $form);
		
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
		$i = 0;
		$eq = array();
		foreach($nodesImport as $index => &$node) {
			$nodes[] = array("label" => $node["text_lemma"], "size" => Lemma::RelativeWeight($node["sentences"], $range["minimum"], $range["maximum"]));
		
			$eq[$node["id_lemma"]] = $i;
			$i += 1;
		}

		$edges = array();
		$edgesImport = Lemma::Links();
		foreach ($edgesImport as $key => &$value) {

			$value["id"] = strval($key + 1);
			$value["source"] = $eq[$value["source"]];
			$value["target"] = $eq[$value["target"]];
			$value["value"] = intval($value["weight"]);
			unset($value["weight"],$value["id"]);


			$edges[] = $value;
		}

		json(array("nodes"=>$nodes, "links" => $edges), $methods = "OPTIONS, GET");
	});

	$app->get('/API/Sigma/:lemmaId', function ($lemmaId) use($app) {
		$nodes = array();
		$nodesImport = Lemma::All($lemma = $lemmaId);
		$range = Lemma::MaxMin($lemma = $lemmaId);

		$i = 0;
		$eq = array();
		foreach($nodesImport as $index => &$node) {
			$nodes[] = array("label" => $node["text_lemma"], "size" => Lemma::RelativeWeight($node["sentences"], $range["minimum"], $range["maximum"] ), "color" => Lemma::RelativeColor($node["sentences"], $range["minimum"], $range["maximum"] ));
			
			$eq[$node["id_lemma"]] = $i;
			$i += 1;
		}

		$edges = array();
		$edgesImport = Lemma::Links($lemma = $lemmaId);
		foreach ($edgesImport as $key => &$value) {

			$value["id"] = strval($key + 1);
			$value["source"] = $eq[$value["source"]];
			$value["target"] = $eq[$value["target"]];
			$value["value"] = intval($value["weight"]);
			unset($value["weight"],$value["id"]);


			$edges[] = $value;
		}
		json(array("nodes"=>$nodes, "links" => $edges), $methods = "OPTIONS, GET");
	});

?>