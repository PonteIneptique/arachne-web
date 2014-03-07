<?php
	session_start();
	
	//oAuth
	require_once('assets/oAuth2/vendor/autoload.php');
	require_once('assets/oAuth1/vendor/autoload.php');
	//End oAuth
		
	define("CLOTHO", true);
	 
	#Require configuration, frameworks, assets 	
	require_once './assets/Slim/Slim.php';
	require_once './config.php';
	require_once './assets/SQL.PDO.php';
    
	#Start the framework
	\Slim\Slim::registerAutoloader();
	$app = new \Slim\Slim();
	
	
	#Requires class
	require_once("./display.php");
	require_once("./class/elements.php");
	require_once("./class/lemma.php");
	require_once("./class/author.php");
	require_once("./class/sentences.php");
	
	require_once("./routes/general.php");
	require_once("./routes/lemma.php");
	require_once("./routes/viz.php");
	require_once("./routes/api.php");
	require_once("./routes/sentence.php");
	

	#Run
	$app->run();
?>
