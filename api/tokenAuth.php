<?php

class tokenAuth extends \Slim\Middleware
{
	public function denyAccess()
	{
     	$res = $this->app->response();
        $res->status(401);
        echo "access denied";
	}

	public function authenticateToken($authorisation)
	{
		$fullToken = json_decode($authorisation);
		$asArray = get_object_vars($fullToken);
		$username = $asArray["username"];
		$token = $asArray["token"];
		$expiry = $asArray["tokenExpiration"];

		$db = new database();
		$data = $db->connectToDB()->select("auth", [
				"username",
				"token",
				"token_expiry",
			],[
				"username" => $username
			]);

		// todo: add checking for token expiry
		// right now just checks for matching token, need that extra level of security.

		if ($token == $data[0]["token"])
		{
			$res = $this->app->response();
       		$res->status(200);
			$this->next->call();
		}
		else
		{
			$this->denyAccess();
		}
	}

	public function call()
	{       
		$authHeader = $this->app->request->headers->get("Authorization");

		if (strlen($authHeader) == 0) $this->denyAccess();
		else if (strlen($authHeader) > 0)
			if ($authHeader == "noToken") $this->next->call();
			else $this->authenticateToken($authHeader);

        /*
        //Check if our token is valid
        if ($this->authenticate($tokenAuth))
        {
            //Get the user and make it available for the controller
            $usrObj = new \Subscriber\Model\User();
            $usrObj->getByToken($tokenAuth);
            $this->app->auth_user = $usrObj;
            //Update token's expiration
            \Subscriber\Controller\User::keepTokenAlive($tokenAuth);
            //Continue with execution
            $this->next->call();
        } else {
            $this->deny_access();
        }
        */
    }

}

?>