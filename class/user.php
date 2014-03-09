<?php
	class User {
	/**
	 * User class
	 *
	 *
	 */
	 
		/**
		 *	Get the DB in a PDO way, can be called through self::DB()->PdoFunctions
		 * @return PDO php object
		 */
		private static function DB() {
			global $DB;
			return $DB;
		}
		
		/**
		 *	Log a user in
		 *
		 * @param $post["password"]		Password User
		 * @param $post["mail"]			Mail identifier
		 * @return Status
		 */
		static function login($post) {
			$pw = hash('sha256', $post["password"]);

			$req = self::DB()->prepare("SELECT name_user as Name, email_user as Mail, id_user as UID FROM user WHERE email_user = ? AND password_user = ?");
			$req->execute(array($post["mail"], $pw));
			
			if($req->rowCount() == 1) {
				return array("signin" => true, "data" => $req->fetch(PDO::FETCH_ASSOC));
			} else {
				return array("signin" => false, "error" => array("signin" => array("message" => "Account not recognized.")));
			}

		}
		
		/**
		 *	Sign a user up
		 *
		 * @param $post["password"]		User's Password
		 * @param $post["user"]			User's Identifier
		 * @param $post["mail"]			User's mail
		 * @param $post["name"]			User's name
		 * @return Status
		 */
		static function signup($post, $id = true) {
			if(isset($post["mail"]) && isset($post["password"]) && isset($post["name"])) {
				$req = "INSERT INTO user (`name_user`,`email_user`,`password_user`) VALUES (? , ? , ?)";
				$req = self::DB()->prepare($req);
				$req->execute(array($post["name"], $post["mail"], hash("sha256", $post["password"])));
				
				if($req->rowCount() == 1) {
					if($id == true) {
						$uid = self::DB()->lastInsertId();
						$_SESSION["user"] = array("id" => $uid, "name" => $post["name"], "mail" => $post["mail"]);
						return array("status" => "success", "uid" => $uid);
					} else {
						return array("status" => "success", "message" => "You have now signed up");
					}
				} else {
					return array("status" => "error", "message" => "Error during sign up. Please contact thibault.clerice[at]kcl.ac.uk or retry.");
				}
			} else {
				return array("status" => "error", "message" => "A field is missing");
			}
		}
		
		/**
		 *	Sign a user in using oAuth. Sign up the user if he doesnt exist.
		 *
		 * @param $provider				Provider Identifier
		 * @param $post["email"]		User's mail
		 * @param $post["name"]			User's name
		 * @return Status and User's Data
		 */
		static function oAuthLogin($data, $provider) {
			//uid
			$data = (array) $data;
			if(!isset($data["name"])) {
				$data["name"] = $data["nickname"];
			}
			if(!isset($data["email"])) {
				$data["email"] = $data["nickname"];
			}
			$req = self::DB()->prepare("SELECT u.name_user as Name, u.email_user as Mail, u.id_user as UID FROM user_oauth uo, user u WHERE u.id_user = uo.id_user AND uo.provider = ? AND uo.external_uid = ? LIMIT 1");
			$req->execute(array($provider, $data["uid"]));
			if($req->rowCount() >= 1) {
				$d = $req->fetch(PDO::FETCH_ASSOC);
				$_SESSION["user"] = array("id" => $d["UID"], "name" => $d["Name"], "mail" => $d["Mail"]);
				return array("signin" => true, "data" => $d);
			} else {
				$sign = self::signup(array("mail" => $data["email"], "name" => $data["name"], "password" => time()), true);
				if($sign["status"] == "success") {
					$req = self::DB()->prepare("INSERT INTO user_oauth VALUES (NULL, ?, ?, ?)");
					$req->execute(array($sign["uid"], $provider, $data["uid"]));
					$_SESSION["user"] = array("id" => $sign["uid"], "name" => $data["name"], "mail" => $data["email"]);
					return array("signin" => true, "data" => array("UID" => $sign, "Name" => $data["name"], "Mail" => $data["email"]));
				} else {
					return array("signin" => false, "status" => "error", "message" => $sign["message"]);
				
				}
			}
		}
		
		/**
		 *	oAuth2 functionalities
		 *
		 *	https://github.com/php-loep/oauth2-client
		 * 
		 *	@param $server	Server name 
		 *	@return array 
		 */
		static function oAuth2($GET, $server, $return = false) {
			switch($server) {
				case "facebook":
					$provider = new League\OAuth2\Client\Provider\Facebook(array(
						'clientId'  =>  FB_ID,
						'clientSecret'  =>  FB_SEC,
						'redirectUri'   =>  FB_URI
					));
					break;
				case "google":
					$provider = new League\OAuth2\Client\Provider\Google(array(
						'clientId'  =>  GGL_ID,
						'clientSecret'  =>  GGL_SEC,
						'redirectUri'   =>  GGL_URI
					));
					break;
				case "github":
					$provider = new League\OAuth2\Client\Provider\Github(array(
						'clientId'  =>  GIT_SEC,
						'clientSecret'  =>  GIT_SEC,
						'redirectUri'   =>  GIT_URI
					));
					break;
			}

			if ( ! isset($GET['code'])) {

				// If we don't have an authorization code then get one
				if($return == true) {

					$d["Auth"] = $provider->authorize(array(), $return);
					return $d;
				}

			} else {

				try {

					// Try to get an access token (using the authorization code grant)
					$t = $provider->getAccessToken('authorization_code', array('code' => $GET['code']));

					try {

						// We got an access token, let's now get the user's details
						$userDetails = $provider->getUserDetails($t);
						$d = self::oAuthLogin($userDetails, $server);

						$d["Location"] = ROOT_URI;
						return $d;
						//return $userDetails;

					} catch (Exception $e) {
						print $e;

						// Failed to get user details

					}

				} catch (Exception $e) {

					// Failed to get access token
					print $e;
				}
			}
		}
		/**
		 *	oAuth1 functionalities
		 *
		 *	https://github.com/php-loep/oauth1-client
		 * 
		 *	@param $server	Server name 
		 *	@return array 
		 */
		static function oAuth1($GET, $prov, $return = false) {
			switch($prov) {
				case "twitter":
					
					$server = new League\OAuth1\Client\Server\Twitter(array(
						'identifier'  =>  TWI_ID,
						'secret'  =>  TWI_SEC,
						'callback_uri'   =>  TWI_URI
					));
					
					break;
			}
			if (isset($GET['user'])) {

				// Check somebody hasn't manually entered this URL in,				// by checkign that we have the token credentials in				// the session.
				if ( ! isset($_SESSION['token_credentials'])) {
					return 'No token credentials.';
					exit(1);
				}

				// Retrieve our token credentials. From here, it's play time!
				$tokenCredentials = unserialize($_SESSION['token_credentials']);
				//Get User details
				$user = $server->getUserDetails($tokenCredentials);
				//Now use our login/sign in class
				$d = self::oAuthLogin($user, $prov);
				if(isset($_SESSION["callback"])) {
					$d["Location"] = $_SESSION["callback"];
				}
				return $d;

			// Step 3
			} elseif (isset($GET['oauth_token']) && isset($GET['oauth_verifier'])) {

				// Retrieve the temporary credentials from step 2
				$temporaryCredentials = unserialize($_SESSION['temporary_credentials']);

				// Third and final part to OAuth 1.0 authentication is to retrieve token
				// credentials (formally known as access tokens in earlier OAuth 1.0
				// specs).
				$tokenCredentials = $server->getTokenCredentials($temporaryCredentials, $GET['oauth_token'], $GET['oauth_verifier']);

				// Now, we'll store the token credentials and discard the temporary
				// ones - they're irrevelent at this stage.
				unset($_SESSION['temporary_credentials']);
				$_SESSION['token_credentials'] = serialize($tokenCredentials);
				session_write_close();

				// Redirect to the user page
				header("Location: /oAuth/".ucfirst($prov)."?user=user");
				exit;

			// Step 2.5 - denied request to authorize client
			} elseif (isset($GET['denied'])) {
				return 'Hey! You denied the client access to your Twitter account! If you did this by mistake, you should <a href="?go=go">try again</a>.';

			// Step 2
			} else {
				
				// First part of OAuth 1.0 authentication is retrieving temporary credentials.
				// These identify you as a client to the server.
				$temporaryCredentials = $server->getTemporaryCredentials();

				// Store the credentials in the session.
				$_SESSION['temporary_credentials'] = serialize($temporaryCredentials);
				session_write_close();

				if($return == true) {
					$d["Auth"] = $server->authorize($temporaryCredentials, $return);
					return $d;
				}
				
				// Second part of OAuth 1.0 authentication is to redirect the
				// resource owner to the login screen on the server.
				$server->authorize($temporaryCredentials);

			// Step 1
			}
		}
	}
?>