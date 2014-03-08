<?php

	/**
	* Render all content in the main.php template using
	* a inner template
	*
	* @param string $template name of template
	* @param string $variables variables to pass to template
	*/
	function display($template, $variables =array() , $scripts = array(), $title = "Home") {
		$app = Slim\Slim::getInstance();
		
		ob_start();
		$app->render($template, $variables);
		$content = ob_get_clean();
		      
		$app->render('_main.php', array('content' => $content, "title" => $title, 'scripts' => $scripts));
	}
	
	
	/**
	* Render variables in JSON using Controls and methods, with application type automatically
	*
	* @param array $variables Array of variables to render in json form
	* @param string $methods Allowed Methods
	* @param int $status HTTP Status
	*/
	function json($variables = false, $methods = "OPTIONS, GET, POST", $status = false) {
		$app = Slim\Slim::getInstance();
		$app->contentType('application/json');
		$response = $app->response();
		$response->header('Access-Control-Allow-Origin', '*'); 
		$response->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-authentication, X-client');
		$response->header('Access-Control-Allow-Methods', $methods);
		
		if($status != false) {
			$app->response()->status($status);
		}
		if($variables != false) {
			echo json_encode($variables);
		}
	}
	
	/*
	 * Returns an associative array with data from Body of a request
	 * Like normal $_POST or $_GET
	 *
	 */
	function getBody() {
		$request = Slim\Slim::getInstance()->request();
		$data = json_decode($request->getBody(), true);
		return $data;
	}
?>
