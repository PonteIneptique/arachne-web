<?php
	
	$app->get('/API/lemme/', function () use($app)  {
		$app->contentType('application/json');
		$query = $app->request->get("query");
		$options = array("count" => false);
		if(strlen($query) > 0) {
			$options["query"] = $query;
		}
		$data = Lemma::Get($options);
		json($data);
	});
	
	$app->get('/API/lemme/item', function ()  use($app) {
		$app->contentType('application/json');
		$data = Lemma::Get();
		json($data);
	});
	$app->get('/API/lemme/link', function ()  use($app) {
		$app->contentType('application/json');
		$data = Lemma::Links();
		json($data);
	});
	$app->get('/API/author/', function ()  use($app) {
		$app->contentType('application/json');
		$data = Author::Get();
		json($data);
	});
	$app->get('/API/lemme/:id/sentence', function ($id) use($app)  {
		$app->contentType('application/json');
		$data = Sentences::Word($id);
		json($data);
	});
	$app->get('/API/author/:id/sentence', function ($id)  use($app) {
		$app->contentType('application/json');
		$data = Sentences::Author($id);
		json($data);
	});
	
	$app->options('/API/D3/', function () {
		$app->contentType('application/json');
		$response = $app->response();
		$response->header('Access-Control-Allow-Origin', '*'); 
		$response->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-authentication, X-client');
		$response->header('Access-Control-Allow-Methods', 'GET, OPTIONS');
	});
	$app->get('/API/sigma/', function () use($app)  {
		$app->contentType('application/json');
		$response = $app->response();
		$response->header('Access-Control-Allow-Origin', '*'); 
		$response->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-authentication, X-client');
		$response->header('Access-Control-Allow-Methods', 'GET, OPTIONS');
		$author = Author::Get();
		$lemma = Lemma::Weight(array("group" => false), "label");
		$links = Lemma::Links(array("group" => false));
		$sentences = Lemma::Sentences();
		$data = array(
			"nodes" => array(),
			"links" => array()
		);
		
		//Formating Authors and Lemma
		// Group 1 : Author
		// Group 2 : Lemma
		// {"name":"Myriel","group":1},
		$ids = array(
			"author" => array(),
			"lemma" => array()
		);
		$i = 1;
		foreach($author as &$aut) {
			$ids["author"][$aut["uid"]] = $i;
			$data["nodes"][] = array("name" => $aut["label"], "group" => 1, "id" => $i, "weight" => $aut["weight"], "attributes" => array(array("for" => "uid", "value" => $aut["uid"])));
			++$i;
		}
		foreach($lemma as &$lem) {
			$ids["lemma"][$lem["uid"]] = $i;
			
			$temp = array();
			
			$array= array("name" => $lem["label"], "group" => 2, "id" => $i, "weight" => $lem["weight"], "attributes" => array(array("for" => "uid", "value" => $lem["uid"])));
			$iii = 1;
			foreach(Metadata::Link($lem["uid"]) as $meta) {
				if($temp["t" . $meta["uid_type_metadata"]] && !in_array("m" . $meta["uid_metadata"], $array["attributes"][$temp["t" . $meta["uid_type_metadata"]]]["value"])) {
					$array["attributes"][$temp["t" . $meta["uid_type_metadata"]]]["value"][] = "m" . $meta["uid_metadata"];
				} else {
					$array["attributes"][$iii] = array("for" => "t" . $meta["uid_type_metadata"], "value" => array("m" . $meta["uid_metadata"]));
					$temp["t" . $meta["uid_type_metadata"]] = $iii;
					$iii++;
				}
			}
			$data["nodes"][]  = $array;
			++$i;
		}
		//Links
		foreach($links as &$link) {
			//{"source":23,"target":18,"value":3},
			$data["links"][] = array("source" => $ids["lemma"][$link["lemma"]], "target" => $ids["author"][$link["author"]], "value" => $link["weight"], "color" => "rgb(192, 57, 43)" );
		}
		
		$sen_lem = array();
		$actualLink = null;
		$s = array();
		//Subgraph
		foreach($sentences as &$sen) {
			if(!isset($s[$sen["uid_sentence"]])) {
				$s[$sen["uid_sentence"]]= array();
			}
			$s[$sen["uid_sentence"]][] = $sen["uid_lemma"];
		}
		
		foreach($s as $SSS => $SS) {
			foreach($SS as $k) {
				foreach($SS as $KK) {
					if($KK != $k) {
						$data["links"][] = array("source" => $ids["lemma"][$k], "target" => $ids["lemma"][$KK], "value" => 1, "color" => "rgb(127, 140, 141)", "sentence" => $SSS);
					}
				}
			}
		}
		json($data);
	});
?>