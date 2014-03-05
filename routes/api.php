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
		json($data);
	});

?>