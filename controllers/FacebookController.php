<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes.php";

class FacebookController{

	static function getLoginUrl(){
		require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload-fb2.php";

		//De URL van waar de login aangeroepen werd bewaren, opdat deze bruikbaar is na de redirect van Facebook
		$_SESSION["fb-login-prev"] = str_replace("/shop/", "", $_SERVER['REQUEST_URI']);
		if($_SESSION["fb-login-prev"] == "404.php") $_SESSION["fb-login-prev"] = "index.php";

		$facebook = new Facebook\Facebook([
		  'app_id' => '1977960159102628',
		  'app_secret' => '8a8a6a382c777fc989e12fc528ad2f25',
		  'default_graph_version' => 'v2.5',
		]);

		$permissions = ['email', 'user_likes'];
		$helper = $facebook->getRedirectLoginHelper();

		return $helper->getLoginUrl($_SERVER['DOCUMENT_ROOT'] . 'controllers/RequestController.php?fb-login-redirect=true', $permissions);
	}
	static function login(){
		global $REDS, $GREENS;

		require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload-fb2.php";
		$facebook = new Facebook\Facebook([
		  'app_id' => '1977960159102628',
		  'app_secret' => '8a8a6a382c777fc989e12fc528ad2f25',
		  'default_graph_version' => 'v2.5',
		]);

		$helper = $facebook->getRedirectLoginHelper();

		$_SESSION['FBRLH_state'] = $_GET['state'];

		try {
		  	$accessToken = $helper->getAccessToken();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  // When Graph returns an error
		  echo 'Graph returned an error: ' . $e->getMessage();
		  exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  // When validation fails or other local issues
		  echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  exit;
		}

		if (isset($accessToken)) {
		  $_SESSION['facebook_access_token'] = (string) $accessToken;
		}

		try {
		  	$response = $facebook->get('/me?locale=en_US&fields=name,email',$_SESSION['facebook_access_token']);
		  	$userNode = $response->getGraphUser();

		  	$id = $userNode->getField('id');
	 	    $fullname = $userNode->getField('name');
	 	    $email = $userNode->getField('email');

		  	$firstname = substr($fullname, 0, strpos($fullname, " "));
	 	    $lastname = substr($fullname, strpos($fullname, " "));

	 	    //Als de user al is ingelogd, en dus slechts zijn account wil linken
	 	    if(isset($_SESSION['user'])){
	 	    	$user = $_SESSION['user'];

	 	    	$otherUser = UserModel::getUserByFacebookId($id);

	 	    	if(!$otherUser){
	 	    		$user->facebook_id = $id;
		 	    	UserModel::updateUser($user);
		 	    	Utils::notify('views/account.php?fb-link-popup', 'Je account is nu gelinkt met Facebook');
		 	    	$_SESSION["user"] = $user;
	 	    	}
	 	    	//Als het Facebookaccount al met een lokaal account gelinkt is
	 	    	else{
	 	    		Utils::notify('views/account.php', 'Je Facebook account is al gelinkt met een ander account', 'red');
	 	    	}
	 	    	
	 	    }
	 	    //Als de user wilt aanmelden/registreren met Facebook
	 	    else{
	 	    	$user = UserModel::getUserByFacebookId($id);

	 	    	//Als de user wilt registreren met Facebook
		 	    if(!$user){
		 	    	$fb_avatar = file_get_contents("http://graph.facebook.com/" . $id . "/picture?width=9999");
					$save = file_put_contents("../resources/images/avatars/fb-" . $id . ".jpg", $fb_avatar);
		 	    	$user = new UserEntity(null, null, $lastname, $firstname, 0, $email, $id, "fb-" . $id . ".jpg", 1);

					$user->id = UserModel::addUser($user);

					$_SESSION["user"] = $user;

			        $url = $_SESSION["fb-login-prev"];
			        unset($_SESSION["fb-login-prev"]);
			        Utils::notify($url, 'Je bent succesvol ingelogd. Welkom!');
		 	    }
		 	    else if($user->active == 0){
		 	    	Utils::notify(PREV_URL, 'Je account is niet meer beschikbaar', 'red');
		 	    }
		 	    else{
		 	    	$_SESSION["user"] = $user;

			        $url = $_SESSION["fb-login-prev"];
			        unset($_SESSION["fb-login-prev"]);
			        Utils::notify($url, 'Je bent succesvol ingelogd. Welkom!');
		 	    }
		        
	 	    }
	 	    

		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  	// When Graph returns an error
		  	echo 'Graph returned an error: ' . $e->getMessage();
		  	exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  	// When validation fails or other local issues
		  	echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  	exit;
		}
	}
}
?>