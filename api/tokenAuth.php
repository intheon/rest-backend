<?php

class tokenAuth extends \Slim\Middleware
{
	public function deny()
	{

	}

	public function authenticate($token)
	{
		$db = new database();
		$data = $db->connectToDB()->select("user","*");
	}

	public function call()
	{       
		$authHeader = $this->app->request->headers->get("Authorization");

		if ($authHeader == "noToken")
		{
			$this->next->call();
		}
		else 
		{
			echo "token present";
		}

		/*

		if ($authHeader === null)
		{
			echo "no header, proceed";
		}
		else
		{
			echo "header, wow";
		}


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