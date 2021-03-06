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
	
	require_once("./class/gamification.php");
	require_once("./class/logs.php");
	require_once("./class/lemma.php");
	require_once("./class/forms.php");
	require_once("./class/sentences.php");
	require_once("./class/user.php");
	require_once("./class/annotations.php");
	require_once("./class/relationships.php");
	require_once("./class/polarities.php");
	
	require_once("./routes/general.php");
	require_once("./routes/lemma.php");
	require_once("./routes/viz.php");
	require_once("./routes/api.php");
	require_once("./routes/sentence.php");
	require_once("./routes/oauth.php");
	require_once("./routes/profile.php");
	require_once("./routes/annotations.php");
	

	#Run
	$app->run();
?>
