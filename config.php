<?php
	if(!defined("CLOTHO")) { exit(); }
	
	##
	#
	#	SQL CONFIG
	#
	##
	define("Wserver", "localhost");
	define("Wport", "3306");
	define("Wuser", "root");
	define("Wpassword", "");
	define("Wtable", "clotoweb");

	##
	#
	#	WebsiteMode
	#
	#####
	define("MODE", "Test");
	
	##
	#
	#	General information
	#
	###
	define("API_URI", "http://".$_SERVER['HTTP_HOST']."/API");
	#define("COOKIE_DOMAIN", $_SERVER['HTTP_HOST']);
	define("ROOT_URI", "http://".$_SERVER['HTTP_HOST']);
	
	##
	#
	#	oAuth Credentials
	#
	###
	define("FB_ID", '227988434072904');
	define("FB_SEC", '0931596e89d80c04b16dd4e62f8f7a60');
	define("FB_URI", ROOT_URI.'/oAuth/Facebook');
	
	define("GGL_ID", '766625943907-5vsgnkgbpai5mjmmkckgaeevr7oloemk.apps.googleusercontent.com');
	define("GGL_SEC", '0iDojuwGM4lfgoaO-DNWPVZ5');
	define("GGL_URI", ROOT_URI.'/oAuth/Google');
	
	define("GIT_ID", '032cdde9e2dd39d6a957');
	define("GIT_SEC", '8aa4bd7bf3271cf5aaa33d32471877b96e6aeac9');
	define("GIT_URI", ROOT_URI.'/oAuth/Github');
	
	define("TWI_ID", 'OWE5zF6p7HzgnMCzMKI3w');
	define("TWI_SEC", 'NHFxk3O4lNsi5oTPw5rb68r3SS8FtLeG4DdkOp7yCs');
	define("TWI_URI", ROOT_URI.'/oAuth/Twitter');
	
?>
