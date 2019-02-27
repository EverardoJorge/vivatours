<?php

function EWD_FEUP_Twitter_Login($PageLink) {
	$Twitter_Key = get_option("EWD_FEUP_Twitter_Key");
	$Twitter_Secret = get_option("EWD_FEUP_Twitter_Secret");
	
	include_once(EWD_FEUP_CD_PLUGIN_PATH . "social/twitteroauth.php");
	
	if (isset($_REQUEST['oauth_token']) && $_COOKIE['EWD_FEUP_token']  !== $_REQUEST['oauth_token']) {
	
		//If token is old, distroy session and redirect user to index.php
		EWD_FEUP_Erase_Twitter_Data();
		header("Location: " . $PageLink . "Reason=Invalid_Token");
		
	} elseif (isset($_REQUEST['oauth_token']) && $_COOKIE['EWD_FEUP_token'] == $_REQUEST['oauth_token']) {
	
		//Successful response returns oauth_token, oauth_token_secret, user_id, and screen_name
		$connection = new TwitterOAuth($Twitter_Key, $Twitter_Secret, $_COOKIE['EWD_FEUP_token'] , $_COOKIE['EWD_FEUP_token_secret']);
		$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
		$access_token_string = serialize($access_token);
		if ($connection->http_code == '200') {
			$user_info = $connection->get('account/verify_credentials'); 
			//$name = explode(" ",$user_info->name);
			//$fname = isset($name[0])?$name[0]:'';
			//$lname = isset($name[1])?$name[1]:'';
	
			//Redirect user to twitter
			setcookie('EWD_FEUP_status', 'verified', time()+3600*24*7, '/');
			setcookie('EWD_FEUP_request_vars', $access_token_string, time()+3600*24*7, '/');
			setcookie('EWD_FEUP_Twitter_Full_Name', $user_info->name, time()+3600*24*7, '/');
			
			//Unset no longer needed request tokens
			setcookie('EWD_FEUP_token', '', time()-3600, '/');
			setcookie('EWD_FEUP_token_secret', '', time()-3600, '/');
			header("Location: " . $PageLink . "Reason=Success");
		} else {
			die("error, try again later!");
		}
			
	} else {
	
		if (isset($_GET["denied"])) {
			header("Location: " . $PageLink . "Reason=Denied");
			die();
		}
	
		//Fresh authentication
		$connection = new TwitterOAuth($Twitter_Key, $Twitter_Secret);
		$request_token = $connection->getRequestToken($PageLink);
		
		//Received token info from twitter
		setcookie('EWD_FEUP_token', $request_token['oauth_token'], time()+3600, '/');
		setcookie('EWD_FEUP_token_secret', $request_token['oauth_token_secret'], time()+3600, '/');
		
		//Any value other than 200 is failure, so continue only if http code is 200
		if($connection->http_code == '200')
		{
			//redirect user to twitter
			$twitter_url = $connection->getAuthorizeURL($request_token['oauth_token']);
			header('Location: ' . $twitter_url); 
		}else{
			die("error connecting to twitter! try again later!");
		}
	}
}

function EWD_FEUP_Erase_Twitter_Data() {
	setcookie('EWD_FEUP_token', '', time()-3600, '/');
	setcookie('EWD_FEUP_token_secret', '', time()-3600, '/');
	setcookie('EWD_FEUP_status', 'verified', time()-3600, '/');
	setcookie('EWD_FEUP_request_vars', $access_token, time()-3600, '/');
	setcookie('EWD_FEUP_Twitter_Full_Name', '', time()-3600, '/');
}

?>